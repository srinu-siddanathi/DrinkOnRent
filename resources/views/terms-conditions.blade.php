<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - Drink On Rent</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', 'Montserrat', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 via-green-100 to-blue-200 min-h-screen">
    <header class="bg-white/80 backdrop-blur shadow-sm sticky top-0 z-40">
        <div class="container mx-auto flex items-center justify-between py-3 px-4 md:px-8">
            <a href="/" class="flex items-center">
                <img src="/uploads/logo.png" alt="Drink On Rent Logo" class="h-12 w-auto">
            </a>
            <span class="text-lg font-semibold text-blue-900">Terms & Conditions</span>
        </div>
    </header>
    <main class="container mx-auto px-4 py-12">
        <div class="glass max-w-3xl mx-auto p-10">
            <h1 class="text-3xl font-bold text-blue-900 mb-6">Terms & Conditions</h1>
            <p class="mb-4 text-blue-800">Welcome to Drink On Rent! These terms and conditions outline the rules and regulations for the use of our website and services.</p>
            <h2 class="text-xl font-semibold text-green-700 mt-8 mb-2">Acceptance of Terms</h2>
            <p class="text-blue-800 mb-4">By accessing this website and using our services, you accept these terms and conditions in full. Do not continue to use Drink On Rent if you do not agree to all of the terms and conditions stated on this page.</p>
            <h2 class="text-xl font-semibold text-green-700 mt-8 mb-2">Service Usage</h2>
            <ul class="list-disc ml-6 text-blue-800 mb-4">
                <li>Our water purifier rental services are subject to availability and eligibility.</li>
                <li>Users must provide accurate and complete information during registration and transactions.</li>
                <li>Unauthorized use of our services or website may give rise to a claim for damages and/or be a criminal offense.</li>
            </ul>
            <h2 class="text-xl font-semibold text-green-700 mt-8 mb-2">Payments & Refunds</h2>
            <ul class="list-disc ml-6 text-blue-800 mb-4">
                <li>All rental payments must be made in advance as per the selected plan.</li>
                <li>Refunds, if applicable, will be processed according to our refund policy.</li>
            </ul>
            <h2 class="text-xl font-semibold text-green-700 mt-8 mb-2">Intellectual Property</h2>
            <p class="text-blue-800 mb-4">All content, trademarks, and data on this website, including but not limited to text, images, logos, and software, are the property of Drink On Rent or its licensors.</p>
            <h2 class="text-xl font-semibold text-green-700 mt-8 mb-2">Limitation of Liability</h2>
            <p class="text-blue-800 mb-4">Drink On Rent will not be liable for any damages arising from the use or inability to use our services or website.</p>
            <h2 class="text-xl font-semibold text-green-700 mt-8 mb-2">Changes to Terms</h2>
            <p class="text-blue-800 mb-4">We reserve the right to update or change these terms at any time. Changes will be posted on this page with an updated effective date.</p>
            <h2 class="text-xl font-semibold text-green-700 mt-8 mb-2">Contact Us</h2>
            <p class="text-blue-800">If you have any questions about these Terms & Conditions, please contact us at <a href="mailto:support@drinkonrent.com" class="text-green-700 underline">support@drinkonrent.com</a>.</p>
        </div>
    </main>
    <footer class="bg-white/80 py-8 mt-10 shadow-inner">
        <div class="container mx-auto px-6 text-center text-blue-800">
            &copy; {{ date('Y') }} Drink On Rent. All rights reserved.
        </div>
    </footer>
</body>
</html> 