@extends('admin.layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Forgot Password</h2>
        <p class="mt-2 text-sm text-gray-500 text-center">Enter your admin email to receive a 6-digit OTP.</p>

        <form method="POST" action="{{ route('admin.password.otp.send') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required
                       class="block w-full h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if(session('success'))
                <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded-md px-3 py-2">
                    {{ session('success') }}
                </div>
            @endif

            <button type="submit" class="w-full h-11 px-5 bg-indigo-600 border border-indigo-700 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                Send OTP
            </button>

            <div class="text-center">
                <a href="{{ route('admin.login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Back to login</a>
            </div>
        </form>
    </div>
</div>
@endsection
