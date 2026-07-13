<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VADownload') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <style type="text/tailwindcss">
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            color: #f8fafc;
        }

        .bg-mesh {
            background-color: #0b1120;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.2) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.1) 0, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .blob {
            position: absolute;
            filter: blur(90px);
            z-index: -1;
            opacity: 0.3;
        }
    </style>
</head>
<body class="font-sans antialiased bg-mesh min-h-screen relative overflow-x-hidden selection:bg-indigo-500/30 flex flex-col justify-center items-center">
    
    <!-- Decorative Blobs -->
    <div class="blob bg-sky-600/30 w-96 h-96 rounded-full top-0 left-0"></div>
    <div class="blob bg-indigo-600/30 w-96 h-96 rounded-full bottom-0 right-0"></div>

    <div class="w-full flex flex-col sm:justify-center items-center pt-6 sm:pt-0 z-10 p-4">
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 rounded-2xl bg-linear-to-tr from-sky-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i data-feather="download-cloud" class="text-white w-8 h-8"></i>
                </div>
                <span class="text-2xl font-bold tracking-tight text-white">VADownload</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-2 px-8 py-10 glass-card rounded-[2rem] shadow-2xl">
            {{ $slot }}
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
