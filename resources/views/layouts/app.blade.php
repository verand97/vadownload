<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS v4 CDN -->
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Feather Icons -->
        <script src="https://unpkg.com/feather-icons"></script>

        <style type="text/tailwindcss">
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #0b1120;
                color: #f8fafc;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-200 bg-[#0b1120] selection:bg-indigo-500/30">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-8">
                {{ $slot }}
            </main>
        </div>
        
        <script>
            // Initialize Feather Icons after Alpine loads
            document.addEventListener('alpine:init', () => {
                feather.replace();
            });
            // Also initialize normally for non-alpine components
            document.addEventListener('DOMContentLoaded', () => {
                feather.replace();
            });
        </script>
    </body>
</html>
