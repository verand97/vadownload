<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VADownload - Unduh Sesukamu</title>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Script Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        /* Custom Glasses */
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Animasi Latar Belakang */
        @keyframes animateBg {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animated-bg {
            background: linear-gradient(-45deg, #0f172a, #1e1b4b, #312e81, #172554);
            background-size: 400% 400%;
            animation: animateBg 15s ease infinite;
        }

        /* Animasi loading pulse */
        .loader {
            border-top-color: #3b82f6;
            -webkit-animation: spinner 1.5s linear infinite;
            animation: spinner 1.5s linear infinite;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="antialiased animated-bg text-gray-100 min-h-screen flex flex-col justify-center items-center p-4 relative overflow-hidden">

    <!-- Ornamen Latar -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse transition-all"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse delay-1000 transition-all"></div>

    <div class="z-10 w-full max-w-3xl glass-panel p-8 md:p-12 rounded-3xl shadow-2xl flex flex-col gap-8 transition-transform transform hover:scale-[1.01] duration-500 relative">
        
        <!-- Logout Button -->
        <div class="absolute top-4 right-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg">
                    Logout ({{ Auth::user()->name ?? '' }})
                </button>
            </form>
        </div>

        <!-- Header Section -->
        <div class="text-center group cursor-default">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-transparent bg-clip-text bg-linear-to-r from-blue-400 via-purple-400 to-pink-400 drop-shadow-lg group-hover:from-pink-400 group-hover:to-blue-400 transition-all duration-700 ease-in-out">
                VADownload
            </h1>
            <p class="mt-4 text-lg md:text-xl text-gray-300 font-light tracking-wide opacity-90">
                Unduh Video, Audio, & Gambar dari IG, TikTok, YouTube dalam Sekejap.
            </p>
        </div>

        <!-- Form Section -->
        <form id="downloadForm" class="flex flex-col sm:flex-row gap-4 mt-4 relative">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <input type="url" id="mediaUrl" required
                    placeholder="Tempel tautan video/audio di sini..."
                    class="w-full pl-12 pr-4 py-4 md:py-5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-inner text-lg outline-none">
            </div>
            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="px-8 py-4 md:py-5 bg-linear-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold rounded-2xl shadow-lg hover:shadow-purple-500/30 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center min-w-[150px] focus:ring-4 focus:ring-purple-500/50">
                <span id="btnText">Unduh Sekarang</span>
                <div id="btnLoader" class="hidden ml-2 w-5 h-5 border-4 border-white border-t-transparent rounded-full loader"></div>
            </button>
        </form>

        <!-- Error State -->
        <div id="errorBox" class="hidden p-4 rounded-2xl bg-red-500/20 border border-red-500/50 text-red-200 text-center animate-fade-in text-sm font-medium">
            <!-- Teks Error -->
        </div>

        <!-- Result Section -->
        <div id="resultBox" class="hidden flex-col gap-6 animate-fade-in mt-2 transition-all duration-500">
            <h3 class="text-2xl font-semibold text-white/90 border-b border-white/10 pb-2">Hasil Unduhan</h3>

            <div class="flex flex-col md:flex-row gap-6 bg-white/5 p-4 rounded-2xl border border-white/10 relative overflow-hidden group">
                <!-- Glow effect di belakang kartu hasil -->
                <div class="absolute inset-0 bg-linear-to-br from-purple-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="w-full md:w-1/3 flex justify-center items-center bg-black/40 rounded-xl overflow-hidden shadow-inner aspect-video md:aspect-auto relative z-10 min-h-[160px]">
                    <!-- Thumbnail Media -->
                    <img id="mediaThumb" src="" alt="Thumbnail" class="w-full h-full object-cover hidden transition-transform duration-700 hover:scale-110">
                    <div id="thumbPlaceholder" class="text-gray-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Detail Media -->
                <div class="w-full md:w-2/3 flex flex-col justify-center space-y-4 relative z-10">
                    <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-wider">
                        <span id="mediaSource" class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full border border-blue-500/30">Sumber</span>
                        <span id="mediaType" class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full border border-purple-500/30 text-xs font-semibold">Tipe</span>
                    </div>

                    <div class="space-y-3">
                        <!-- Link Download Video (Jika ada) -->
                        <a id="btnDownloadVideo" href="#" target="_blank" rel="noopener noreferrer" class="hidden items-center justify-between w-full px-5 py-3 bg-linear-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white font-medium rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            <span>Unduh Video (Kualitas Terbaik)</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </a>

                        <!-- Link Download Audio (Jika ada) -->
                        <a id="btnDownloadAudio" href="#" target="_blank" rel="noopener noreferrer" class="hidden items-center justify-between w-full px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-medium rounded-xl transition-all duration-300 transform hover:-translate-y-1">
                            <span>Unduh Audio (MP3)</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </a>

                        <!-- Jika hasil bentuk file gambar galeri dll didukung (Misal IG Carousel) -->
                        <p id="extraNotice" class="text-xs text-gray-400 italic hidden">Saran: Klik kanan pada pratinjau dan pilih "Simpan Gambar" jika media berupa gambar.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-4 text-center text-sm text-gray-400/80">
            <p>Dilengkapi desain antarmuka modern yang dibuat dengan ❤ untuk pengalaman mengunduh terbaik.</p>
            <p class="mt-1 text-xs">Aplikasi ini menggunakan public API untuk merender respons. Kami tidak menyimpan media apa pun.</p>
        </div>

    </div>

    <script>
        // Logika UI Frontend & Axios Fetch
        const form = document.getElementById('downloadForm');
        const mediaUrl = document.getElementById('mediaUrl');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');
        const errorBox = document.getElementById('errorBox');
        const resultBox = document.getElementById('resultBox');

        // Konfigurasi Axios CSRF
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Elemen-elemen Box Hasil
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
            resultBox.classList.add('hidden');

            // Set loading state
            btnText.innerText = "Memproses...";
            btnLoader.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');

            try {
                const response = await axios.post('/api/download', {
                    url: mediaUrl.value
                });

                const resData = response.data;

                if (resData.success) {
                    renderResult(resData.data);
                } else {
                    showError(resData.message || 'Gagal memproses tautan, pastikan URL tersebut didukung.');
                }
            } catch (err) {
                const errMsg = err.response?.data?.message || err.response?.data?.error?.text || 'Terjadi kesalahan sistem atau URL tidak didukung!';
                showError(errMsg);
            } finally {
                // Reset loading state
                btnText.innerText = "Unduh Sekarang";
                btnLoader.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
            }
        });

        function renderResult(data) {
            // Cobalt responses structure:
            // status: "redirect" / "stream" / "success" / "picker"
            // url: string
            console.log(data); // for debug

            // Menentukan platform dari input URL basic string
            let urlStr = mediaUrl.value.toLowerCase();
            let platformName = "Platform";
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

            // Cobalt API V10 mengembalikan data.url untuk video utama. 
            // Jika itu carousel / picker, mengembalikan data.picker

            if (data.url) {
                btnDownloadVideo.href = data.url;
                btnDownloadVideo.classList.remove('hidden');
                btnDownloadVideo.classList.add('flex');

                // Set nama file download jika API menyediakannya (Ryzendesu title feature)
                if (data.filenamePattern) {
                    btnDownloadVideo.setAttribute('download', data.filenamePattern + '.mp4');
                } else {
                    btnDownloadVideo.removeAttribute('download');
                }
            }

            // Thumbnail / Poster (Opsional karena beberapa API eksternal tidak memberikan thumbnail langsung di endpoint dasar)
            const thumbUrl = data.thumbnail || data.poster || null;

            if (thumbUrl) {
                mediaThumb.src = thumbUrl;
                mediaThumb.classList.remove('hidden');
                thumbPlaceholder.classList.add('hidden');
            } else {
                mediaThumb.classList.add('hidden');
                thumbPlaceholder.classList.remove('hidden');
            }

            form.reset();
            resultBox.classList.remove('hidden');

            // Smooth scroll down ke result area
            resultBox.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        function showError(msg) {
            errorBox.innerText = `Error: ${msg}`;
            errorBox.classList.remove('hidden');
        }
    </script>
</body>

</html>