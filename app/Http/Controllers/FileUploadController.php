<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    private function findHeaderRowPattern(array $data, array $patterns)
    {
        foreach ($data as $i => $row) {
            $normalizedRow = array_map(function($v) {
                return mb_strtolower(trim(preg_replace('/[\s\p{Cc}\p{Cf}]+/u', ' ', $v)));
            }, $row);

            $foundHeaders = [];
            foreach ($patterns as $key => $pattern) {
                foreach ($normalizedRow as $cell) {
                    if (preg_match($pattern, $cell)) {
                        $foundHeaders[$key] = true;
                        break;
                    }
                }
            }
            if (count($foundHeaders) === count($patterns)) {
                return [$i, $row];
            }
        }
        return [-1, []];
    }

    // Парсим число для Количества, округляя до целого (убирая дробную часть)
    private function parseQuantity($str)
    {
        $str = trim($str);
        $str = str_replace(' ', '', $str);
        $str = str_replace(',', '.', $str);
        return (int)floor((float)$str);
    }

    // Форматируем целое число с пробелами
    private function formatQuantity($number)
    {
        return number_format($number, 0, '', ' ');
    }

    // Преобразуем строку из файла Выручки без изменений (оставляем как есть)
    private function parseRevenue($str)
    {
        return $str;
    }

    // Для подсчёта суммы Выручки превращаем в число
    private function parseRevenueForSum($str)
    {
        $str = trim($str);
        $str = str_replace(',', '', $str);
        /* $str = str_replace(',', '.', $str); */
        return (float)$str;
    }

   public function upload(Request $request)
    {
        $this->cleanOldFiles();

        $request->validate(['file' => 'required|mimes:xls,xlsx']);

        $path = $request->file('file')->store('uploads', 'local');
        $fullPath = storage_path('app/' . $path);

        if (!file_exists($fullPath)) {
            return back()->withErrors('Файл не найден: ' . $fullPath);
        }

        $spreadsheet = IOFactory::load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $patterns = [
            'Номенклатура' => '/номенклатур[а-я]*/ui',
            'Количество'   => '/количеств[о-я]*|кол-во/ui',
            'Выручка'      => '/выручк[а-я]*/ui',
        ];

        // 1. Сначала пробуем универсальный вариант (единая строка-заголовок)
        list($headerIndex, $headerRow) = $this->findHeaderRowPattern($data, $patterns);
        $format = 'unified';

        if ($headerIndex !== -1) {
            // Получение индексов колонок
            $indexes = [];
            $normalizedHeaderRow = array_map(function($v) {
                return mb_strtolower(trim(preg_replace('/[\s\p{Cc}\p{Cf}]+/u', ' ', $v)));
            }, $headerRow);
            foreach ($patterns as $key => $pattern) {
                foreach ($normalizedHeaderRow as $idx => $cell) {
                    if (preg_match($pattern, $cell)) {
                        $indexes[$key] = $idx;
                        break;
                    }
                }
            }
            $startDataIndex = $headerIndex + 1;
            $dataRows = array_slice($data, $startDataIndex);

        } else {
            // 2. Старый формат: по отдельным строкам "Номенклатура" + "Количество" и "Выручка"
            $nomenklaturaIndex = -1;
            $colsIndex = -1;
            foreach ($data as $i => $row) {
                if (in_array('Номенклатура', $row)) $nomenklaturaIndex = $i;
                if (in_array('Количество', $row) && in_array('Выручка', $row)) $colsIndex = $i;
            }
            if ($nomenklaturaIndex === -1 || $colsIndex === -1) {
                return back()->withErrors('Не найдены необходимые строки');
            }
            $nomenklaturaRow = $data[$nomenklaturaIndex];
            $colsRow = $data[$colsIndex];
            $indexes = [];
            $indexes['Номенклатура'] = array_search('Номенклатура', $nomenklaturaRow);
            $indexes['Количество']   = array_search('Количество', $colsRow);
            $indexes['Выручка']      = array_search('Выручка', $colsRow);

            $startDataIndex = max($nomenklaturaIndex, $colsIndex) + 1;
            $dataRows = array_slice($data, $startDataIndex);
            $format = 'split';
        }

        // --- Обработка данных ---
        $filteredData = [];
        $sumKol = 0;
        $sumVir = 0.0;

        foreach ($dataRows as $row) {
    $nameRaw = $row[$indexes['Номенклатура']] ?? '';
    $name = trim($nameRaw);
    if (
        empty($name) ||
        mb_strtolower($name) === 'итого' ||
        $name === '#NULL!' ||
        mb_strpos($name, '#') === 0 ||
        preg_match('/^=|\+/', $name)
    ) {
        continue;
    }
    $kol = $this->parseQuantity($row[$indexes['Количество']] ?? '0');
    $virRaw = $row[$indexes['Выручка']] ?? '0';
    $virNum = $this->parseRevenueForSum($virRaw);

    $filteredData[] = [
        'Номенклатура' => $name,
        'Количество' => $kol,
        'Выручка' => $virNum,
    ];
}
    $dataCount = count($filteredData);
        // Итоговая строка
        $filteredData[] = [
            'Номенклатура' => 'Итого',
            'Количество'   => "=SUM(B2:B" . ($dataCount + 1) . ")",
            'Выручка'      => "=SUM(C2:C" . ($dataCount + 1) . ")"
        ];

        $newSpreadsheet = new Spreadsheet();
        $newSheet = $newSpreadsheet->getActiveSheet();
        $newSheet->fromArray(array_keys($filteredData[0]), null, 'A1');
        $newSheet->fromArray(array_map('array_values', $filteredData), null, 'A2');

        $newSheet->getColumnDimension('A')->setAutoSize(true);

        $newSheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '6D542C'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F7F2DD']
            ]
        ]);
        $totalRow = count($filteredData) + 1;
        $newSheet->getStyle("A{$totalRow}:C{$totalRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '6D542C'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F7F2DD']
            ]
        ]);

        $filteredFileName = 'filtered_result_' . time() . '.xlsx';
        $filteredFilePath = storage_path('app/uploads/' . $filteredFileName);
        $writer = new Xlsx($newSpreadsheet);
        $writer->save($filteredFilePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        return back()->with([
            'success' => 'Файл успешно обработан!',
            'filtered_file' => $filteredFileName,
        ]);
    }

    public function downloadFiltered($filename)
    {
        $file = storage_path('app/uploads/' . $filename);
        if (!file_exists($file)) {
            abort(404);
        }
        return response()->download($file);
    }

    // Метод очистки старых файлов
    public function cleanOldFiles()
    {
        $files = \Storage::files('uploads');
        $now = time();
        foreach ($files as $file) {
            if ($now - \Storage::lastModified($file) > 86400) {
                \Storage::delete($file);
            }
        }
    }
}
