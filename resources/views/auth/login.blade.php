<link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" type="image/x-icon">

<x-guest-layout>
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Mi Logo" class="w-32 mx-auto" width="150px">
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Nombre de Usuario -->
        <div>
            <x-input-label for="nombre" :value="__('Nombre de Usuario')" />
            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="contrasena" :value="__('Contraseña')" />
            <x-text-input id="contrasena" class="block mt-1 w-full" type="password" name="contrasena" required
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('contrasena')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Iniciar sesión') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>