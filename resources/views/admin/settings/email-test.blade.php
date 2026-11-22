@extends('admin.layouts.app')

@section('title', 'Email Test')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900">SMTP Email Test</h2>
        <p class="mt-1 text-sm text-gray-500">Send a test email and check the server mail configuration.</p>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500">Mailer</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $mailer ?: 'Not configured' }}</p>
            </div>
            <div class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500">SMTP Host</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $host ?: 'Not configured' }}</p>
            </div>
            <div class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500">Port / Scheme</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $port ?: '-' }} / {{ $scheme ?: 'default' }}</p>
            </div>
            <div class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500">From</p>
                <p class="mt-1 text-sm font-semibold text-gray-900 break-all">{{ $fromName }} &lt;{{ $fromAddress }}&gt;</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.email-test.send') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Test recipient</label>
                <input id="email" name="email" type="email" required value="{{ old('email', $adminEmail) }}"
                       class="block w-full max-w-md h-11 px-3 rounded-md border-2 border-gray-400 bg-white text-gray-900 focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                @error('email')
                    <p class="mt-2 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md px-3 py-2 break-words">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex h-11 items-center justify-center rounded-md border border-blue-700 bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                Send Test Email
            </button>
        </form>
    </div>

    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
        If delivery fails, the exact error is displayed here and logged in <strong>storage/logs/laravel.log</strong>. After changing <strong>.env</strong>, run <strong>php artisan optimize:clear</strong> to reload mail configuration.
    </div>
</div>
@endsection
