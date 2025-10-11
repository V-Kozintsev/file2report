<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Мини-Игра') }}
        </h2>
    </x-slot>
    <div x-data="gameClick" class='max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8'>
        <button class='text-test' @click='toggle' >Играть</button>
        <div x-show="open" x-transition>
            <p  x-cloak>текст</p>
            <canvas id="game-canvas" class=""></canvas>
        </div>
    </div>

</x-app-layout>
