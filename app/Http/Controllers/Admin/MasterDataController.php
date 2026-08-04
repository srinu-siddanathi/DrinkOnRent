<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Service;
use App\Models\SparePart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    public function index()
    {
        return view('admin.settings.masters');
    }

    public function areas(Request $request): JsonResponse
    {
        $areas = Area::query()->orderBy('name')->paginate(8, ['*'], 'page', (int) $request->get('page', 1));

        return response()->json($areas);
    }

    public function storeArea(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name',
        ]);

        $area = Area::create($validated);

        return response()->json([
            'message' => 'Area created successfully.',
            'area' => $area,
        ], 201);
    }

    public function updateArea(Request $request, Area $area): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('areas', 'name')->ignore($area->id),
            ],
        ]);

        DB::transaction(function () use ($area, $validated): void {
            $oldName = $area->name;
            $newName = $validated['name'];

            if ($oldName !== $newName) {
                Customer::where('area', $oldName)->update(['area' => $newName]);
            }

            $area->update($validated);
        });

        return response()->json([
            'message' => 'Area updated successfully.',
            'area' => $area->fresh(),
        ]);
    }

    public function destroyArea(Area $area): JsonResponse
    {
        if (Customer::where('area', $area->name)->exists()) {
            return response()->json([
                'message' => 'This area is in use by customers and cannot be deleted.',
            ], 422);
        }

        $area->delete();

        return response()->json([
            'message' => 'Area deleted successfully.',
        ]);
    }

    public function spareParts(Request $request): JsonResponse
    {
        $spareParts = SparePart::query()->orderBy('name')->paginate(8, ['*'], 'page', (int) $request->get('page', 1));

        return response()->json($spareParts);
    }

    public function storeSparePart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spare_parts,name',
        ]);

        $sparePart = SparePart::create($validated);

        return response()->json([
            'message' => 'Spare part created successfully.',
            'spare_part' => $sparePart,
        ], 201);
    }

    public function updateSparePart(Request $request, SparePart $sparePart): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('spare_parts', 'name')->ignore($sparePart->id),
            ],
        ]);

        DB::transaction(function () use ($sparePart, $validated): void {
            $oldName = $sparePart->name;
            $newName = $validated['name'];

            if ($oldName !== $newName) {
                Service::whereJsonContains('spare_parts', $oldName)
                    ->chunkById(100, function ($services) use ($oldName, $newName): void {
                        foreach ($services as $service) {
                            $updatedParts = collect($service->spare_parts ?? [])
                                ->map(fn ($part) => $part === $oldName ? $newName : $part)
                                ->values()
                                ->all();

                            $service->update(['spare_parts' => $updatedParts]);
                        }
                    });
            }

            $sparePart->update($validated);
        });

        return response()->json([
            'message' => 'Spare part updated successfully.',
            'spare_part' => $sparePart->fresh(),
        ]);
    }

    public function destroySparePart(SparePart $sparePart): JsonResponse
    {
        if (Service::whereJsonContains('spare_parts', $sparePart->name)->exists()) {
            return response()->json([
                'message' => 'This spare part is in use in service history and cannot be deleted.',
            ], 422);
        }

        $sparePart->delete();

        return response()->json([
            'message' => 'Spare part deleted successfully.',
        ]);
    }
}
