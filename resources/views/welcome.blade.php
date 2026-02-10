<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drink On Rent - With Added Minerals</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', 'Montserrat', sans-serif;
        scroll-behavior: smooth;
    }
    html {
        scroll-behavior: smooth;
    }
    .mobile-menu-open {
        display: block !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255,255,255,0.95);
        z-index: 1000;
        padding-top: 80px;
        animation: fadeIn 0.2s;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .glass {
        background: rgba(255, 255, 255, 0.7);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .floating-btn {
        position: fixed;
        bottom: 32px;
        right: 32px;
        z-index: 50;
        background: linear-gradient(90deg, #22d3ee 0%, #4ade80 100%);
        color: white;
        border-radius: 50%;
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 24px rgba(34, 211, 238, 0.3);
        transition: transform 0.2s;
    }

    .floating-btn:hover {
        transform: scale(1.08) rotate(-6deg);
        box-shadow: 0 8px 32px rgba(34, 211, 238, 0.4);
    }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-100 via-green-100 to-blue-200 min-h-screen">
    <!-- Sticky Header -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur shadow-sm">
        <div class="container mx-auto flex items-center justify-between py-3 px-4 md:px-8">
            <a href="#" class="flex items-center">
                <img src="/uploads/logo.png" alt="Drink On Rent Logo" class="h-12 w-auto">
            </a>
            <nav class="hidden md:flex space-x-6">
                <a href="#about" class="text-blue-900 font-semibold hover:text-green-600 transition">About</a>
                <a href="#features" class="text-blue-900 font-semibold hover:text-green-600 transition">Features</a>
                <a href="#plans" class="text-blue-900 font-semibold hover:text-green-600 transition">Plans</a>
                <a href="#testimonials"
                    class="text-blue-900 font-semibold hover:text-green-600 transition">Testimonials</a>
                <a href="#contact" class="text-blue-900 font-semibold hover:text-green-600 transition">Contact</a>
            </nav>
            <button class="md:hidden p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-400" id="menuBtn">
                <svg id="menuIcon" class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="closeIcon" class="w-7 h-7 text-green-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div class="md:hidden hidden" id="mobileMenu">
            <nav class="flex flex-col items-center space-y-6 pb-4 text-lg">
                <a href="#about" class="text-blue-900 font-semibold hover:text-green-600 transition py-2">About</a>
                <a href="#features" class="text-blue-900 font-semibold hover:text-green-600 transition py-2">Features</a>
                <a href="#plans" class="text-blue-900 font-semibold hover:text-green-600 transition py-2">Plans</a>
                <a href="#testimonials" class="text-blue-900 font-semibold hover:text-green-600 transition py-2">Testimonials</a>
                <a href="#contact" class="text-blue-900 font-semibold hover:text-green-600 transition py-2">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative flex items-center justify-center min-h-[70vh] overflow-hidden">
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80"
            alt="Water Purifier" class="absolute inset-0 w-full h-full object-cover object-center opacity-60">
        <div class="container relative z-10 flex flex-col md:flex-row items-center justify-between py-16 px-6">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <div class="glass p-8 md:p-12 animate-fade-in-up">
                    <h1 class="text-5xl md:text-6xl font-extrabold text-blue-900 mb-4 leading-tight drop-shadow">Pure
                        Water, Pure Life</h1>
                    <p class="text-xl text-blue-800 mb-8">Rent the best water purifiers with added minerals for your
                        home or office. Enjoy healthy, great-tasting water every day—no large upfront costs, just pure
                        convenience!</p>
                    <a href="#plans"
                        class="inline-block bg-green-500 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:bg-green-600 transition">View
                        Plans</a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <div class="glass p-4 md:p-8 flex flex-col items-center animate-fade-in">
                    <img src="/uploads/logo.png" alt="Drink On Rent Logo" class="h-24 mb-4">
                    <span class="text-lg text-green-700 font-semibold">With Added Minerals</span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20">
        <div class="container mx-auto px-6 text-center">
            <div class="glass max-w-3xl mx-auto p-10">
                <h2 class="text-4xl font-bold text-blue-900 mb-4">Why Drink On Rent?</h2>
                <p class="text-lg text-blue-800">We believe everyone deserves access to clean, mineral-rich water. Our
                    rental plans make it easy and affordable to enjoy advanced water purification technology without the
                    hassle of ownership or maintenance.</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gradient-to-br from-blue-100 via-green-100 to-blue-200">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-blue-900 text-center mb-12">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="glass p-8 rounded-2xl shadow-lg text-center hover:scale-105 transition">
                    <img src="https://img.icons8.com/ios-filled/100/4caf50/water.png"
                        class="mx-auto mb-4 h-20 animate-bounce" alt="Purity">
                    <h3 class="text-2xl font-semibold text-green-600 mb-2">Advanced Purification</h3>
                    <p class="text-blue-800">Multi-stage filtration removes bacteria, viruses, and impurities for safe,
                        pure water.</p>
                </div>
                <div class="glass p-8 rounded-2xl shadow-lg text-center hover:scale-105 transition">
                    <img src="https://img.icons8.com/ios-filled/100/4caf50/minerals.png"
                        class="mx-auto mb-4 h-20 animate-pulse" alt="Minerals">
                    <h3 class="text-2xl font-semibold text-green-600 mb-2">Added Minerals</h3>
                    <p class="text-blue-800">Essential minerals are added to every drop, supporting your health and
                        taste preferences.</p>
                </div>
                <div class="glass p-8 rounded-2xl shadow-lg text-center hover:scale-105 transition">
                    <img src="https://img.icons8.com/ios-filled/100/4caf50/maintenance.png"
                        class="mx-auto mb-4 h-20 animate-spin-slow" alt="Maintenance">
                    <h3 class="text-2xl font-semibold text-green-600 mb-2">Free Maintenance</h3>
                    <p class="text-blue-800">We handle all servicing and filter changes—no extra cost, no worries!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans Section -->
    <section id="plans" class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-blue-900 text-center mb-12">Our Rental Plans</h2>
            @if($roPlans->count())
            <h3 class="text-2xl font-semibold text-green-700 mb-6">RO Plans</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
                @foreach($roPlans as $plan)
                <div
                    class="glass border-2 border-green-200 rounded-2xl p-10 text-center shadow-lg hover:scale-105 transition">
                    <h3 class="text-2xl font-bold text-green-600 mb-2">{{ $plan->name }}</h3>
                    <p class="text-4xl font-bold text-blue-900 mb-4">₹{{ number_format($plan->price, 2) }}<span
                            class="text-base font-medium text-blue-800">/{{ $plan->duration_in_days }} days</span></p>
                    <ul class="text-blue-800 mb-6 space-y-2">
                        <li>✔️ {{ $plan->litres }}L Storage</li>
                        @if($plan->description)
                        <li>{{ $plan->description }}</li>
                        @endif
                    </ul>
                    <a href="#contact"
                        class="bg-green-500 text-white px-6 py-2 rounded-xl font-semibold shadow-lg hover:bg-green-600 transition">Get
                        Started</a>
                </div>
                @endforeach
            </div>
            @endif
            @if($alkalinePlans->count())
            <h3 class="text-2xl font-semibold text-blue-700 mb-6">Alkaline Plans</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($alkalinePlans as $plan)
                <div
                    class="glass border-2 border-blue-200 rounded-2xl p-10 text-center shadow-lg hover:scale-105 transition">
                    <h3 class="text-2xl font-bold text-blue-600 mb-2">{{ $plan->name }}</h3>
                    <p class="text-4xl font-bold text-blue-900 mb-4">₹{{ number_format($plan->price, 2) }}<span
                            class="text-base font-medium text-blue-800">/{{ $plan->duration_in_days }} days</span></p>
                    <ul class="text-blue-800 mb-6 space-y-2">
                        <li>✔️ {{ $plan->litres }}L Storage</li>
                        @if($plan->description)
                        <li>{{ $plan->description }}</li>
                        @endif
                    </ul>
                    <a href="#contact"
                        class="bg-green-500 text-white px-6 py-2 rounded-xl font-semibold shadow-lg hover:bg-green-600 transition">Get
                        Started</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gradient-to-br from-green-100 via-blue-100 to-green-200">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-blue-900 text-center mb-12">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="glass p-8 rounded-2xl shadow-lg text-center">
                    <p class="text-lg text-blue-800 mb-4">"The water tastes amazing and the service is top-notch. Highly
                        recommended!"</p>
                    <span class="block text-green-700 font-semibold">— Priya S.</span>
                </div>
                <div class="glass p-8 rounded-2xl shadow-lg text-center">
                    <p class="text-lg text-blue-800 mb-4">"No more worries about maintenance. The team handles
                        everything!"</p>
                    <span class="block text-green-700 font-semibold">— Rahul M.</span>
                </div>
                <div class="glass p-8 rounded-2xl shadow-lg text-center">
                    <p class="text-lg text-blue-800 mb-4">"Affordable plans and pure water for my family. Love it!"</p>
                    <span class="block text-green-700 font-semibold">— Anjali K.</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-blue-900 text-center mb-8">Contact Us</h2>
            <form id="contactForm" class="max-w-xl mx-auto glass p-10 rounded-2xl shadow-lg" method="POST" action="/contact">
                @csrf
                <div id="contact-alert"></div>
                <div class="mb-6">
                    <label class="block text-blue-900 font-semibold mb-2" for="name">Name</label>
                    <input class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" type="text" id="name" name="name" required>
                </div>
                <div class="mb-6">
                    <label class="block text-blue-900 font-semibold mb-2" for="email">Email</label>
                    <input class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" type="email" id="email" name="email" required>
                </div>
                <div class="mb-6">
                    <label class="block text-blue-900 font-semibold mb-2" for="mobile">Mobile Number</label>
                    <input class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" type="tel" id="mobile" name="mobile" required pattern="[0-9]{10}" maxlength="10">
                </div>
                <div class="mb-6">
                    <label class="block text-blue-900 font-semibold mb-2" for="message">Message</label>
                    <textarea class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" id="message" name="message" rows="4" required></textarea>
                </div>
                <button class="bg-green-500 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:bg-green-600 transition w-full" type="submit">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Floating Contact Button -->
    <a href="#contact" class="floating-btn" title="Contact Us">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 10.5a8.38 8.38 0 01-.9 3.8c-.5 1-1.1 2-1.9 2.8a8.5 8.5 0 01-12-12c.8-.8 1.8-1.4 2.8-1.9A8.38 8.38 0 0113.5 3c.3 0 .5.2.5.5v2a.5.5 0 01-.5.5A6.5 6.5 0 006.5 13a.5.5 0 01-.5-.5v-2a.5.5 0 01.5-.5A4.5 4.5 0 0113 6.5a.5.5 0 01.5-.5h2a.5.5 0 01.5.5z" />
        </svg>
    </a>

    <!-- Footer -->
    <footer class="bg-white/80 py-8 mt-10 shadow-inner">
        <div class="container mx-auto px-6 text-center text-blue-800">
            <div class="mb-2">
                <a href="/privacy-policy" class="text-green-700 underline hover:text-green-900 mx-2">Privacy Policy</a>
                |
                <a href="/terms-conditions" class="text-green-700 underline hover:text-green-900 mx-2">Terms &
                    Conditions</a>
            </div>
            &copy; {{ date('Y') }} Drink On Rent. All rights reserved.
        </div>
    </footer>

    <script>
    // Mobile menu toggle with overlay and icon swap
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');
    menuBtn.addEventListener('click', function() {
        if (mobileMenu.classList.contains('mobile-menu-open')) {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('mobile-menu-open');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        } else {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('mobile-menu-open');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        }
    });
    // Close mobile menu when a link is clicked
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('mobile-menu-open');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        });
    });
    // Slow spin animation for maintenance icon
    document.querySelectorAll('.animate-spin-slow').forEach(function(el) {
        el.animate([{
                transform: 'rotate(0deg)'
            },
            {
                transform: 'rotate(360deg)'
            }
        ], {
            duration: 6000,
            iterations: Infinity
        });
    });
    document.getElementById('contactForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const alertBox = document.getElementById('contact-alert');
        alertBox.innerHTML = '';
        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value
                },
                body: formData
            });
            const text = await response.text();
            if (response.ok) {
                alertBox.innerHTML = '<div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center" role="alert">Thank you for contacting us! We will get back to you soon.</div>';
                form.reset();
            } else {
                let msg = 'Something went wrong. Please try again.';
                try {
                    const data = JSON.parse(text);
                    if (data.errors) {
                        msg = Object.values(data.errors).flat().join('<br>');
                    } else if (data.message) {
                        msg = data.message;
                    }
                } catch {}
                alertBox.innerHTML = `<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center" role="alert">${msg}</div>`;
            }
        } catch (err) {
            alertBox.innerHTML = '<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center" role="alert">Network error. Please try again.</div>';
        }
    });
    // Smooth scroll with offset for sticky header
    function offsetScrollTo(hash) {
        const target = document.querySelector(hash);
        if (target) {
            const header = document.querySelector('header');
            const headerHeight = header ? header.offsetHeight : 0;
            const elementPosition = target.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({
                top: elementPosition - headerHeight - 10, // 10px extra space
                behavior: 'smooth'
            });
        }
    }
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const hash = this.getAttribute('href');
            if (hash.length > 1 && document.querySelector(hash)) {
                e.preventDefault();
                offsetScrollTo(hash);
                // Optionally close mobile menu
                const menu = document.getElementById('mobileMenu');
                if (menu && !menu.classList.contains('hidden')) menu.classList.add('hidden');
            }
        });
    });
    </script>
</body>

</html>