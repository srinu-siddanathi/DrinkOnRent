@extends('admin.layouts.app')

@section('title', 'Support Request Details')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Support Request #{{ $supportRequest->id }}</h2>
                <div class="flex items-center gap-4">
                    @if($supportRequest->customer_id)
                        <button type="button" onclick="openAddServiceModal()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Add Service
                        </button>
                    @endif
                    <a href="{{ route('admin.support-requests.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to List</a>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Customer</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ optional($supportRequest->customer)->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Mobile Number</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ optional($supportRequest->customer)->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $supportRequest->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-500">Subject</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $supportRequest->subject }}</p>
                </div>
                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-500">Message</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $supportRequest->message }}</p>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Service Details</h3>

                <div class="mt-4 overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full">
                        <table class="w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Date</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Reminder</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spare Parts</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Images</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($supportRequest->services as $service)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->service_date?->format('d-m-Y') ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->next_service_reminder }} Months</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->expiry_date?->format('d-m-Y') ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            @if(!empty($service->spare_parts))
                                                {{ implode(', ', $service->spare_parts) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            @if(!empty($service->images))
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($service->images as $image)
                                                        <img
                                                            src="{{ asset('uploads/service-images/' . $image) }}"
                                                            alt="Service Image"
                                                            class="w-12 h-12 rounded object-cover border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                                            onclick="openServiceImageLightbox('{{ asset('uploads/service-images/' . $image) }}')"
                                                        >
                                                    @endforeach
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">No services linked to this support request yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Update Request</h3>
                <form method="POST" action="{{ route('admin.support-requests.update', $supportRequest) }}" class="mt-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="open" {{ $supportRequest->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $supportRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $supportRequest->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $supportRequest->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Admin Notes</label>
                        <textarea name="admin_notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $supportRequest->admin_notes }}</textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($supportRequest->customer_id)
    <div id="addServiceModal" class="hidden fixed inset-0 z-50 transition-opacity duration-300 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.75);">
        <div class="relative p-0 border-0 w-11/12 md:w-3/4 lg:max-w-lg shadow-lg rounded-xl bg-gray-100 transform transition-all duration-300 scale-95 opacity-0 -translate-y-10 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center px-6 py-4 bg-blue-800 rounded-t-xl flex-shrink-0">
                <h3 class="text-xl font-semibold text-white">Add New Service</h3>
                <button type="button" onclick="closeAddServiceModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto">
                <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $supportRequest->customer_id }}">
                    <input type="hidden" name="support_request_id" value="{{ $supportRequest->id }}">

                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Spare Parts</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                $parts = ['Sediment', 'Spun', 'Post/Carbon', 'Membrane Housing', 'Pump', 'Float', 'Pipe', 'Carbon', 'Tap', 'Membrane', 'SV', 'SMPS', 'Diveter Wall'];
                            @endphp
                            @foreach($parts as $part)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="spare_parts[]" value="{{ $part }}" class="rounded-sm border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ $part }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Service Date & Reminder</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Service Date</label>
                                <input type="date" name="service_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Next Service Reminder</label>
                                <div class="mt-2 grid grid-cols-3 gap-2">
                                    <label class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-200 px-2 py-2">
                                        <input type="radio" name="next_service_reminder" value="3" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700 font-medium">3M</span>
                                    </label>
                                    <label class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-200 px-2 py-2">
                                        <input type="radio" name="next_service_reminder" value="6" checked class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700 font-medium">6M</span>
                                    </label>
                                    <label class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-200 px-2 py-2">
                                        <input type="radio" name="next_service_reminder" value="12" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700 font-medium">12M</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Upload Images</h4>
                        <input type="file" name="images[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    </div>

                    <div class="mt-8 flex justify-end space-x-4 flex-shrink-0">
                        <button type="button" onclick="closeAddServiceModal()" class="px-6 py-2 rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 rounded-md text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Save Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="serviceImageLightbox" class="hidden fixed inset-0 flex items-center justify-center p-4" style="z-index: 9999; background-color: rgba(0,0,0,0.85);">
        <button type="button" onclick="closeServiceImageLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-200">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="serviceImageLightboxImg" src="" alt="Service Image" class="max-h-[85vh] max-w-[95vw] object-contain rounded-lg shadow-2xl" />
    </div>
    @endif
@endsection 

@push('scripts')
<script>
    function openAddServiceModal() {
        const modal = document.getElementById('addServiceModal');
        const panel = modal?.firstElementChild;
        if (!modal || !panel) return;

        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            panel.classList.remove('scale-95', 'opacity-0', '-translate-y-10');
            panel.classList.add('scale-100', 'opacity-100', 'translate-y-0');
        });
    }

    function closeAddServiceModal() {
        const modal = document.getElementById('addServiceModal');
        const panel = modal?.firstElementChild;
        if (!modal || !panel) return;

        panel.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
        panel.classList.add('scale-95', 'opacity-0', '-translate-y-10');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function openServiceImageLightbox(imageUrl) {
        const lightbox = document.getElementById('serviceImageLightbox');
        const image = document.getElementById('serviceImageLightboxImg');
        if (!lightbox || !image) return;

        image.src = imageUrl;
        lightbox.classList.remove('hidden');
    }

    function closeServiceImageLightbox() {
        const lightbox = document.getElementById('serviceImageLightbox');
        const image = document.getElementById('serviceImageLightboxImg');
        if (!lightbox || !image) return;

        image.src = '';
        lightbox.classList.add('hidden');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeServiceImageLightbox();
            closeAddServiceModal();
        }
    });

    document.getElementById('serviceImageLightbox')?.addEventListener('click', function (event) {
        if (event.target.id === 'serviceImageLightbox') {
            closeServiceImageLightbox();
        }
    });
</script>
@endpush