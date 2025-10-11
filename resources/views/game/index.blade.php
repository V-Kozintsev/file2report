<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Мини-Игра') }}
        </h2>
    </x-slot>

    <div x-data='gameClick'>
        <button @click='toggle'>Покажи текст</button>
        <p x-show='open' x-cloak>текст</p>
        <div x-show='open'>
            <canvas id="game-canvas"></canvas>
        </div>
    </div>

</x-app-layout>
