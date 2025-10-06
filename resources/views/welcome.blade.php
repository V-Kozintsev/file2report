<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-800 antialiased">
    {{-- Top navigation --}}
    <header class="border-b bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-900">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                            Register
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <section class="py-20">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Быстрый старт проекта {{ config('app.name') }}
                </h1>
                <p class="mt-4 text-base text-gray-600">
                    Минимальный стартовый экран без лишней графики, в едином стиле Tailwind.
                </p>
                <div class="mt-8 flex items-center justify-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Перейти в кабинет
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                            Зарегистрироваться
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Войти
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="border-t bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </footer>
</body>
</html>
