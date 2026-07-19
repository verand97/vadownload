<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VADownload - Premium Media Downloader</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Script Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <style type="text/tailwindcss">
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            color: #f8fafc;
        }

        .glass-nav {
            background: rgba(11, 17, 32, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-mesh {
            background-color: #0b1120;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.2) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.1) 0, transparent 50%);
        }

        .loader {
            border-top-color: #38bdf8;
            animation: spinner 1s linear infinite;
        }

        @keyframes spinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .blob {
            position: absolute;
            filter: blur(90px);
            z-index: -1;
            opacity: 0.4;
            animation: float 10s infinite ease-in-out alternate;
        }
        
        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(-20px) scale(1.1); }
        }
    </style>
</head>

<body class="antialiased bg-mesh min-h-screen flex flex-col relative overflow-x-hidden selection:bg-indigo-500/30">

    <!-- Decorative Blobs -->
    <div class="blob bg-sky-600/30 w-96 h-96 rounded-full top-[-10%] left-[-10%]"></div>
    <div class="blob bg-indigo-600/30 w-96 h-96 rounded-full bottom-[20%] right-[-10%] animation-delay-2000"></div>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2 cursor-pointer" onclick="window.scrollTo(0,0)">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-sky-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i data-feather="download-cloud" class="text-white w-5 h-5"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">VADownload</span>
            </div>
            
            @if (Route::has('login'))
                <div class="flex items-center gap-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 rounded-full glass-card hover:bg-white/10 transition-colors">
                                <span class="text-sm font-medium text-white">{{ Auth::user()->name }}</span>
                                <i data-feather="chevron-down" class="w-4 h-4 text-slate-400"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 mt-2 w-48 rounded-xl glass-card py-2 shadow-xl border border-white/10">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition-colors">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-400/10 transition-colors">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium px-5 py-2 rounded-full bg-white text-slate-900 hover:bg-slate-200 transition-colors">Sign up</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-32 pb-20 flex flex-col items-center justify-center relative z-10 px-4">
        
        <!-- Hero Section -->
        <div class="text-center max-w-4xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card text-sky-400 text-sm font-medium mb-6">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                </span>
                VADownload v2.0 is now live
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight">
                Download Media <br class="hidden md:block"/>
                <span class="gradient-text">Without Limits.</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-400 font-light max-w-2xl mx-auto">
                The fastest, most reliable tool to download high-quality videos, audio, and images from your favorite social platforms instantly.
            </p>
        </div>

        <!-- Download Card -->
        <div class="w-full max-w-3xl glass-card p-2 md:p-3 rounded-[2rem] shadow-2xl shadow-black/50 mb-16">
            <form id="downloadForm" class="flex flex-col sm:flex-row gap-2 relative">
                <div class="relative w-full flex items-center">
                    <div class="absolute left-6 text-slate-400">
                        <i data-feather="link" class="w-5 h-5"></i>
                    </div>
                    <input type="url" id="mediaUrl" required
                        placeholder="Paste your video or audio link here..."
                        class="w-full pl-14 pr-6 py-5 bg-transparent text-white placeholder-slate-500 focus:outline-none text-lg">
                </div>
                <button type="submit" id="submitBtn" class="px-8 py-4 sm:py-0 bg-white text-slate-900 hover:bg-slate-200 font-semibold rounded-[1.5rem] shadow-lg transition-all duration-300 flex items-center justify-center min-w-[160px]">
                    <span id="btnText">Extract</span>
                    <div id="btnLoader" class="hidden ml-2 w-5 h-5 border-3 border-slate-900 border-t-transparent rounded-full loader"></div>
                </button>
            </form>
        </div>

        <!-- Error State -->
        <div id="errorBox" class="hidden w-full max-w-2xl mb-8 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 text-center animate-fade-in text-sm font-medium items-center justify-center gap-2">
            <i data-feather="alert-circle" class="w-4 h-4"></i>
            <span id="errorText"></span>
        </div>

        <!-- Result Section -->
        <div id="resultBox" class="hidden w-full max-w-4xl glass-card p-6 md:p-8 rounded-[2rem] mb-16 animate-fade-in transition-all duration-500">
            <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
                <h3 class="text-xl font-semibold text-white">Extraction Ready</h3>
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Media Preview -->
                <div class="w-full md:w-5/12 bg-slate-900/50 rounded-2xl overflow-hidden shadow-inner aspect-video md:aspect-[4/5] relative group flex items-center justify-center border border-white/5">
                    <img id="mediaThumb" src="" alt="Thumbnail" class="w-full h-full object-cover hidden transition-transform duration-700 group-hover:scale-105">
                    <div id="thumbPlaceholder" class="text-slate-600 flex flex-col items-center gap-3">
                        <i data-feather="image" class="w-12 h-12"></i>
                        <span class="text-sm font-medium">No Preview</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="w-full md:w-7/12 flex flex-col justify-center space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center border border-white/10">
                            <i data-feather="info" class="w-4 h-4 text-sky-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Source Platform</p>
                            <p id="mediaSource" class="font-semibold text-white">Unknown</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-white/5">
                        <a id="btnDownloadVideo" href="#" target="_blank" rel="noopener noreferrer" class="hidden items-center justify-center gap-2 w-full px-6 py-4 bg-linear-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            <i data-feather="video" class="w-5 h-5"></i>
                            <span>Download High Quality</span>
                        </a>

                        <a id="btnDownloadAudio" href="#" target="_blank" rel="noopener noreferrer" class="hidden items-center justify-center gap-2 w-full px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium rounded-xl transition-all duration-300">
                            <i data-feather="music" class="w-5 h-5"></i>
                            <span>Download Audio Only</span>
                        </a>
                        
                        <p id="extraNotice" class="text-xs text-slate-500 text-center mt-4 hidden">
                            <i data-feather="info" class="w-3 h-3 inline mr-1"></i> Tip: Right-click preview to save images directly.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supported Platforms -->
        <div class="w-full max-w-5xl mx-auto mt-10 text-center">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-widest mb-8">Supported Platforms</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex items-center gap-2 font-bold text-xl"><i data-feather="youtube"></i> YouTube</div>
                <div class="flex items-center gap-2 font-bold text-xl"><i data-feather="instagram"></i> Instagram</div>
                <div class="flex items-center gap-2 font-bold text-xl"><svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg> TikTok</div>
                <div class="flex items-center gap-2 font-bold text-xl"><i data-feather="twitter"></i> Twitter/X</div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="w-full max-w-6xl mx-auto mt-32 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-card p-8 rounded-3xl">
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 flex items-center justify-center text-sky-400 mb-6">
                    <i data-feather="zap"></i>
                </div>
                <h4 class="text-xl font-bold mb-3">Lightning Fast</h4>
                <p class="text-slate-400 leading-relaxed">Our advanced extraction servers process your requests in milliseconds, delivering the media you need without the wait.</p>
            </div>
            
            <div class="glass-card p-8 rounded-3xl border-indigo-500/30 shadow-indigo-500/10">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-6">
                    <i data-feather="shield"></i>
                </div>
                <h4 class="text-xl font-bold mb-3">100% Secure & Private</h4>
                <p class="text-slate-400 leading-relaxed">We respect your privacy. No downloaded files are logged or stored on our servers, ensuring complete anonymity.</p>
            </div>

            <div class="glass-card p-8 rounded-3xl">
                <div class="w-12 h-12 rounded-2xl bg-pink-500/10 flex items-center justify-center text-pink-400 mb-6">
                    <i data-feather="star"></i>
                </div>
                <h4 class="text-xl font-bold mb-3">No Watermarks</h4>
                <p class="text-slate-400 leading-relaxed">Get the original quality exactly as intended. Download content cleanly without any intrusive logos or watermarks.</p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-white/5 py-8 mt-auto relative z-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between text-slate-500 text-sm">
            <p>&copy; {{ date('Y') }} VADownload. All rights reserved.</p>
            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Contact</a>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Frontend Logic & Axios Fetch
        const form = document.getElementById('downloadForm');
        const mediaUrl = document.getElementById('mediaUrl');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');
        const errorBox = document.getElementById('errorBox');
        const errorText = document.getElementById('errorText');
        const resultBox = document.getElementById('resultBox');

        // Axios CSRF config
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Result Box Elements
        const mediaThumb = document.getElementById('mediaThumb');
        const thumbPlaceholder = document.getElementById('thumbPlaceholder');
        const mediaSource = document.getElementById('mediaSource');
        const btnDownloadVideo = document.getElementById('btnDownloadVideo');
        const btnDownloadAudio = document.getElementById('btnDownloadAudio');
        const extraNotice = document.getElementById('extraNotice');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Reset states
            errorBox.classList.add('hidden');
            errorBox.classList.remove('flex');
            resultBox.classList.add('hidden');
            resultBox.classList.remove('opacity-100');

            // Set loading state
            btnText.innerText = "Processing";
            btnLoader.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

            try {
                const response = await axios.post('/api/download', {
                    url: mediaUrl.value
                });

                const resData = response.data;

                if (resData.success) {
                    renderResult(resData.data);
                } else {
                    showError(resData.message || 'Failed to process link. Please ensure the URL is supported.');
                }
            } catch (err) {
                const errMsg = err.response?.data?.message || err.response?.data?.error?.text || 'System error or unsupported URL!';
                showError(errMsg);
            } finally {
                // Reset loading state
                btnText.innerText = "Extract";
                btnLoader.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });

        function renderResult(data) {
            let urlStr = mediaUrl.value.toLowerCase();
            let platformName = "Other Platform";
            if (urlStr.includes('youtube.com') || urlStr.includes('youtu.be')) platformName = "YouTube";
            else if (urlStr.includes('tiktok.com')) platformName = "TikTok";
            else if (urlStr.includes('instagram.com')) platformName = "Instagram";
            else if (urlStr.includes('twitter.com') || urlStr.includes('x.com')) platformName = "X (Twitter)";

            mediaSource.innerText = platformName;

            // Reset Result Buttons
            btnDownloadVideo.classList.remove('flex');
            btnDownloadVideo.classList.add('hidden');
            btnDownloadAudio.classList.remove('flex');
            btnDownloadAudio.classList.add('hidden');
            extraNotice.classList.add('hidden');

            btnDownloadVideo.href = "#";
            btnDownloadAudio.href = "#";

            if (data.url) {
                btnDownloadVideo.href = data.url;
                btnDownloadVideo.classList.remove('hidden');
                btnDownloadVideo.classList.add('flex');

                if (data.filenamePattern) {
                    btnDownloadVideo.setAttribute('download', data.filenamePattern + '.mp4');
                } else {
                    btnDownloadVideo.removeAttribute('download');
                }
            }

            const thumbUrl = data.thumbnail || data.poster || null;

            if (thumbUrl) {
                mediaThumb.src = thumbUrl;
                mediaThumb.classList.remove('hidden');
                thumbPlaceholder.classList.add('hidden');
            } else {
                mediaThumb.classList.add('hidden');
                thumbPlaceholder.classList.remove('hidden');
            }

            // form.reset(); // User might want to see the link they just pasted, so comment this out
            resultBox.classList.remove('hidden');
            
            // Fade in effect
            setTimeout(() => {
                resultBox.classList.add('opacity-100');
            }, 50);

            // Smooth scroll down to result area
            resultBox.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function showError(msg) {
            errorText.innerText = msg;
            errorBox.classList.remove('hidden');
            errorBox.classList.add('flex');
        }
    </script>
</body>
</html>