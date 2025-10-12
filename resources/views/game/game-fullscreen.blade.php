<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class='h-screen flex justify-center items-center'>
    <div x-data="gameModal" class="text-center">
        <button x-show="!open" @click="toggle" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Играть
        </button>
        <div x-cloak x-show="open"
             class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
            <canvas id="game-canvas"></canvas>
        </div>
    </div>
</body>
</html>
