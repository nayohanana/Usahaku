<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'No',
            'Invoice',
            'Tanggal',
            'Total',
            'Kasir',
        ];
    }

    public function map($sale): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $sale->invoice_number,
            \Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i'),
            'Rp ' . number_format($sale->grand_total, 0, ',', '.'),
            $sale->user->name,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $total = $this->sales->sum('grand_total');
                
                $sheet->appendRows([
                    [
                        '',
                        '',
                        'TOTAL',
                        'Rp ' . number_format($total, 0, ',', '.'),
                        '',
                    ]
                ], $sheet);

                $sheet->getStyle('C' . ($lastRow + 1) . ':D' . ($lastRow + 1))
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