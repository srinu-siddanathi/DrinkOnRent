<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Panel</title>
    <!-- simplelightbox is used by the service‑history modal; use local copy so we don't depend on CDN availability -->
    <link rel="stylesheet" href="{{ asset('vendor/simple-lightbox/simple-lightbox.min.css') }}" />
    <script src="{{ asset('vendor/simple-lightbox/simple-lightbox.min.js') }}"></script>
    @vite(['node_modules/jquery-ui-dist/jquery-ui.min.css', 'node_modules/jquery-ui-dist/jquery-ui.min.js'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body.menu-open {
            overflow: hidden;
        }
        
        /* Sidebar collapsed state */
        #sidebar.sidebar-collapsed {
            width: 80px;
        }
        
        #sidebar.sidebar-collapsed .logo-section img {
            display: none;
        }
        
        #sidebar.sidebar-collapsed nav {
            padding: 12px 0;
        }
        
        #sidebar.sidebar-collapsed nav span {
            display: none;
        }
        
        #sidebar.sidebar-collapsed nav a {
            justify-content: center;
            padding: 16px 8px;
            margin: 0;
        }
        
        #sidebar.sidebar-collapsed nav .space-y-4 {
            space-y: 0;
            gap: 0;
        }
        
        #sidebar.sidebar-collapsed nav .space-y-4 > * {
            margin: 0;
        }
        
        #sidebar.sidebar-collapsed nav a:hover {
            padding: 16px 8px;
        }
        
        #sidebar.sidebar-collapsed .logout-section {
            padding: 8px 4px;
        }
        
        #sidebar.sidebar-collapsed .logout-section button {
            justify-content: center;
            padding: 12px 0;
        }
        
        #sidebar.sidebar-collapsed .logout-section button svg {
            margin-right: 0;
        }
        
        #sidebar.sidebar-collapsed .logout-section span {
            display: none;
        }
        
        /* Only slide on mobile */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                width: 16rem;
            }
            
            #sidebar.sidebar-collapsed {
                width: 16rem;
            }
            
            #sidebar.sidebar-collapsed .logo-section img {
                display: block;
            }
            
            #sidebar.sidebar-collapsed nav {
                padding: 24px 0;
            }
            
            #sidebar.sidebar-collapsed nav span {
                display: inline;
            }
            
            #sidebar.sidebar-collapsed nav a {
                justify-content: flex-start;
                padding: 12px 16px;
                margin: 0;
            }
            
            #sidebar.sidebar-collapsed .logout-section {
                padding: 16px;
            }
            
            #sidebar.sidebar-collapsed .logout-section button {
                justify-content: flex-start;
                padding: 10px 16px;
            }
            
            #sidebar.sidebar-collapsed .logout-section button svg {
                margin-right: 12px;
            }
            
            #sidebar.sidebar-collapsed .logout-section span {
                display: inline;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-blue-900 transform transition-transform duration-300 -translate-x-full md:translate-x-0 fixed md:relative left-0 top-0 h-screen md:h-auto z-40">
            <div class="flex flex-col h-screen">
                <!-- Logo -->
                <div class="logo-section flex items-center justify-between px-6 h-16 bg-white">
                    <img src="/uploads/logo.png" alt="Drink On Rent Logo" style="height:48px; width:auto;">
                    <button id="toggleSidebar" class="text-blue-900 hover:text-blue-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-6 py-6">
                    <div class="space-y-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('admin.customers.create') }}" 
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.customers.create') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span class="font-medium">Register Customer</span>
                        </a>

                        <a href="{{ route('admin.customers.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.customers.*') && !request()->routeIs('admin.customers.bin') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-medium">All Customers</span>
                        </a>

                        <a href="{{ route('admin.services.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200
                               {{ request()->routeIs('admin.services.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium">Services</span>
                        </a>

                        <!-- <a href="{{ route('admin.orders.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.orders.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="font-medium">Orders</span>
                        </a> -->

                        <a href="{{ route('admin.support-requests.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.support-requests.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-medium">Support</span>
                        </a>

                        <a href="{{ route('admin.purifiers.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.purifiers.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <span class="font-medium">Purifiers</span>
                        </a>
                        
                        
                        <a href="{{ route('admin.plans.index') }}" 
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.plans.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Plans</span>
                        </a>

                        <a href="{{ route('admin.payments.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200 
                               {{ request()->routeIs('admin.payments.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="font-medium">Payments</span>
                        </a>
                        <a href="{{ route('admin.complaints.index') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200
                               {{ request()->routeIs('admin.complaints.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            <span class="font-medium">Complaints</span>
                        </a>

                        <a href="{{ route('admin.customers.bin') }}"
                           class="flex items-center px-4 py-3 text-base rounded-lg transition-colors duration-200
                               {{ request()->routeIs('admin.customers.bin') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span class="font-medium">Bin</span>
                        </a>
                    </div>
                </nav>

                <!-- Logout -->
                <div class="logout-section p-4 border-t border-blue-800">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
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

        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const isMobile = () => window.innerWidth < 768;

            // Restore sidebar state from localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true' && !isMobile()) {
                sidebar.classList.add('sidebar-collapsed');
            }

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', () => {
                    if (isMobile()) {
                        // Mobile: slide sidebar in/out
                        if (sidebar.classList.contains('-translate-x-full')) {
                            sidebar.classList.remove('-translate-x-full');
                            sidebar.classList.add('translate-x-0');
                            mobileOverlay.classList.remove('hidden');
                        } else {
                            sidebar.classList.add('-translate-x-full');
                            sidebar.classList.remove('translate-x-0');
                            mobileOverlay.classList.add('hidden');
                        }
                    } else {
                        // Desktop: collapse/expand sidebar and save state
                        sidebar.classList.toggle('sidebar-collapsed');
                        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                        localStorage.setItem('sidebarCollapsed', isCollapsed);
                    }
                });

                // Close mobile sidebar when overlay is clicked
                mobileOverlay.addEventListener('click', () => {
                    if (isMobile()) {
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('translate-x-0');
                        mobileOverlay.classList.add('hidden');
                    }
                });

                // Close sidebar when a link is clicked (mobile only)
                const navLinks = sidebar.querySelectorAll('nav a');
                navLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (isMobile() && !sidebar.classList.contains('-translate-x-full')) {
                            sidebar.classList.add('-translate-x-full');
                            sidebar.classList.remove('translate-x-0');
                            mobileOverlay.classList.add('hidden');
                        }
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>