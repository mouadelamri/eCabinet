<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Welcome back to eCabinet</h2>
        <p class="text-gray-500 text-sm font-medium">Veuillez renseigner vos identifiants pour accéder à votre espace.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nom@etablissement.fr" 
                   class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white transition-all duration-200 ease-in-out shadow-sm placeholder:text-gray-400" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-teal-600 hover:text-teal-500 transition-colors duration-200">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" 
                       class="block w-full px-4 py-3 bg-gray-50 border border-transparent rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white transition-all duration-200 ease-in-out shadow-sm placeholder:text-gray-400" />
                <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer opacity-40 hover:opacity-100 transition-opacity duration-200">
                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center pt-2">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 transition-colors duration-200">
            <label for="remember_me" class="ml-2 block text-sm font-medium text-gray-700">
                Remember me
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-[#007F6D] hover:bg-[#006657] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 ease-in-out hover:shadow-lg transform hover:-translate-y-0.5">
                Se connecter
            </button>
        </div>
    </form>

    <div class="mt-10 pt-8 border-t border-gray-100 text-center">
        <p class="text-sm font-medium text-gray-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-bold text-[#007F6D] hover:text-[#006657] ml-1 transition-colors duration-200 hover:underline underline-offset-4">
                S'inscrire
            </a>
        </p>
    </div>
</x-guest-layout>
