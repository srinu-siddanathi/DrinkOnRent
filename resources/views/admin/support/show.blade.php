@extends('admin.layouts.app')

@section('title', 'Support Request Details')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Support Request #{{ $supportRequest->id }}</h2>
                <a href="{{ route('admin.support-requests.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to List</a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Customer</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $supportRequest->customer->name }}</p>
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
@endsection 