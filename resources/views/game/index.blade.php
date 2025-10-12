<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Мини-Игра1') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <a  href=" {{ route('game.fullscreen') }} "
            target="_blank"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
        >
            Играть
        </a>
    </div>

</x-app-layout>
