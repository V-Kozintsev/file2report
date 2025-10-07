<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Отчёты
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                    @if (session('filtered_file'))
                        <div class="mt-2">
                            <a href="{{ route('reports.download', session('filtered_file')) }}"
                               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                                Скачать результат
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <div class="rounded-lg bg-white p-6 shadow">
                <form action="{{ route('reports.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">Загрузить Excel (.xls, .xlsx)</label>
                        <input id="file" name="file" type="file" accept=".xls,.xlsx"
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-2 text-xs text-gray-500">Максимум 10 МБ</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                            Обработать
                        </button>
                        <a href="{{ route('reports.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-6 rounded-lg bg-white p-4 text-sm text-gray-600 shadow">
                <p class="mb-1">Как работает:</p>
                <ul class="list-disc pl-5">
                    <li>Ищем заголовки “Номенклатура”, “Количество”, “Выручка”.</li>
                    <li>Количество округляется до целых и форматируется.</li>
                    <li>Выручка суммируется, итоговая строка — “Итого”.</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
