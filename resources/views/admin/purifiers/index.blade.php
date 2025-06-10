@extends('admin.layouts.app')

@section('title', 'Purifiers')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Purifiers</h2>
            <!-- <a href="{{ route('admin.purifiers.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Purifier
                </a> -->
        </div>

        <div class="mt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Serial Number</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Model</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Next Service</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($purifiers as $purifier)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purifier->serial_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purifier->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $purifier->type === 'ro' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $purifier->type_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $purifier->status === 'available' ? 'bg-green-100 text-green-800' : 
                                       ($purifier->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 
                                       ($purifier->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($purifier->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purifier->customer ? $purifier->customer->name : 'Not Assigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purifier->next_service_date ? $purifier->next_service_date->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.purifiers.show', $purifier) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('admin.purifiers.edit', $purifier) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $purifiers->links() }}
        </div>
    </div>
</div>
@endsection