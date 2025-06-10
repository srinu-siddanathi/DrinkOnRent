<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('purifier_type')->orderBy('price')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purifier_type' => 'required|in:ro,alkaline',
            'litres' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purifier_type' => 'required|in:ro,alkaline',
            'litres' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully');
    }

    public function destroy(Plan $plan)
    {
        // Check if plan has any subscriptions before deleting
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Cannot delete plan that has active subscriptions.');
        }

        $plan->delete();
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully');
    }
} 