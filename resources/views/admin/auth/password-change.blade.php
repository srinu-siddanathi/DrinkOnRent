@extends('admin.layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-1">Change your password</h2>
        <p class="text-sm text-gray-500 mb-6">Use a strong password that you do not use elsewhere.</p>

        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password" required
                       class="block w-full max-w-md h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required
                       class="block w-full max-w-md h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                <p class="mt-1 text-xs text-gray-500">At least 8 characters, with uppercase, lowercase, number and symbol.</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                       class="block w-full max-w-md h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center justify-center min-w-40 h-11 px-5 bg-blue-600 border border-blue-700 text-white text-sm font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
