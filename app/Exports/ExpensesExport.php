<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    public function collection()
    {
        return $this->expenses;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kategori',
            'Deskripsi',
            'Jumlah',
            'Kasir',
        ];
    }

    public function map($expense): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            \Carbon\Carbon::parse($expense->date)->format('d/m/Y'),
            $expense->category,
            $expense->description,
            'Rp ' . number_format($expense->amount, 0, ',', '.'),
            $expense->user->name,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $total = $this->expenses->sum('amount');
                
                $sheet->appendRows([
                    [
                        '',
                        '',
                        '',
                        'TOTAL',
                        'Rp ' . number_format($total, 0, ',', '.'),
                        '',
                    ]
                ], $sheet);

                $sheet->getStyle('D' . ($lastRow + 1) . ':E' . ($lastRow + 1))
                    ->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFF00'],
                        ],
                    ]);
            },
        ];
    }
}