@extends('admin.layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Reset Password</h2>
        <p class="mt-2 text-sm text-gray-500 text-center">Enter the OTP and choose a strong new password.</p>

        @if(session('success'))
            <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md px-3 py-2">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.reset') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">6-digit OTP</label>
                <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required
                       class="block w-full h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 tracking-widest focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                @error('otp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required
                       class="block w-full h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                <p class="mt-1 text-xs text-gray-500">At least 8 characters, with uppercase, lowercase, number and symbol.</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                       class="block w-full h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
            </div>

            <button type="submit" class="w-full h-11 px-5 bg-indigo-600 border border-indigo-700 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                Update Password
            </button>
        </form>

        <form method="POST" action="{{ route('admin.password.otp.resend') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Resend OTP</button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800">Back to login</a>
        </div>
    </div>
</div>
@endsection
