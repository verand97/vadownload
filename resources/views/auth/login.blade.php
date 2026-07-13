<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-400 font-medium text-sm text-center" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
        <p class="text-slate-400 text-sm">Sign in to continue to VADownload</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Username Address -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Username') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                    <i data-feather="user" class="w-5 h-5"></i>
                </div>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="username"
                    class="block w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Enter your username">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-medium text-slate-300">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                    <i data-feather="lock" class="w-5 h-5"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-900 text-indigo-500 shadow-sm focus:ring-indigo-500 focus:ring-offset-slate-900" name="remember">
                <span class="ms-2 text-sm text-slate-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all transform hover:-translate-y-0.5">
                {{ __('Sign In') }}
            </button>
        </div>
        
        @if (Route::has('register'))
        <div class="text-center mt-6">
            <p class="text-sm text-slate-400">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">Sign up</a>
            </p>
        </div>
        @endif
    </form>
</x-guest-layout>
