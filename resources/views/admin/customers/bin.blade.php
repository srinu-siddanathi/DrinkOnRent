@extends('admin.layouts.app')

@section('title', 'Deleted Customers')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-medium text-gray-900">Deleted Customers</h2>
            </div>
            
            <div class="mt-4 overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full">
                    <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $customer)
                        <tr>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->first_name }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->phone }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->area }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->deleted_at->format('jS M Y g:i A') }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <form action="{{ route('admin.customers.restore', $customer->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Restore">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.customers.force-delete', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this customer? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete Permanently">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No deleted customers found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
