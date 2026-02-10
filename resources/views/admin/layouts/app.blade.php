<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900">
            <div class="flex flex-col h-screen">
                <!-- Logo -->
                <div class="flex items-center px-6 h-16 bg-gray-800">
                    <img src="/uploads/logo.png" alt="Drink On Rent Logo" style="height:48px; width:auto;">
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-6 py-6">
                    <div class="space-y-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('admin.customers.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-600 text-white' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-medium">Customers</span>
                        </a>

                        <a href="{{ route('admin.orders.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="font-medium">Orders</span>
                        </a>

                        <a href="{{ route('admin.support-requests.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.support-requests.*') ? 'bg-indigo-600 text-white' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-medium">Support</span>
                        </a>

                        <a href="{{ route('admin.purifiers.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.purifiers.*') ? 'bg-indigo-600 text-white' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <span class="font-medium">Purifiers</span>
                        </a>

                        <a href="{{ route('admin.plans.index') }}" 
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.plans.*') ? 'bg-indigo-600 text-white' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Plans</span>
                        </a>

                        <a href="{{ route('admin.payments.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="font-medium">Payments</span>
                        </a>
                    </div>
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t border-gray-700">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm font-medium text-gray-100 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('title')</h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html> 