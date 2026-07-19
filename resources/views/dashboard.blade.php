<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="glass-card rounded-[2rem] p-8 shadow-2xl shadow-black/50">
            <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-6">
                <div class="w-12 h-12 rounded-xl bg-linear-to-tr from-sky-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i data-feather="grid" class="text-white w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Admin Dashboard</h2>
                    <p class="text-slate-400 text-sm">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-full bg-sky-500/20 flex items-center justify-center text-sky-400">
                            <i data-feather="download"></i>
                        </div>
                        <span class="text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-full">+12%</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-1">Total Downloads</p>
                    <h3 class="text-3xl font-bold text-white">1,284</h3>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                            <i data-feather="users"></i>
                        </div>
                        <span class="text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-full">+5%</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-1">Active Users</p>
                    <h3 class="text-3xl font-bold text-white">342</h3>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-full bg-pink-500/20 flex items-center justify-center text-pink-400">
                            <i data-feather="server"></i>
                        </div>
                        <span class="text-xs font-medium text-sky-400 bg-sky-400/10 px-2 py-1 rounded-full">Stable</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-1">Server Status</p>
                    <h3 class="text-3xl font-bold text-white">99.9%</h3>
                </div>
            </div>

            <!-- Recent Activity Placeholder -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
                <div class="bg-slate-900/50 rounded-2xl border border-white/5 p-6 flex flex-col items-center justify-center text-center">
                    <i data-feather="activity" class="w-12 h-12 text-slate-500 mb-3"></i>
                    <p class="text-slate-400 text-sm">No recent activity found. Stats will appear here once users start downloading.</p>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <a href="/" class="px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm font-medium rounded-xl transition-all flex items-center gap-2">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Back to Downloader
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
