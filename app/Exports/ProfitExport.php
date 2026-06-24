<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $totalSales;
    protected $totalExpenses;
    protected $profit;
    protected $profitMargin;

    public function __construct($totalSales, $totalExpenses, $profit, $profitMargin)
    {
        $this->totalSales = $totalSales;
        $this->totalExpenses = $totalExpenses;
        $this->profit = $profit;
        $this->profitMargin = $profitMargin;
    }

    public function array(): array
    {
        return [
            [
                'Pendapatan',
                'Rp ' . number_format($this->totalSales, 0, ',', '.'),
            ],
            [
                'Pengeluaran',
                'Rp ' . number_format($this->totalExpenses, 0, ',', '.'),
            ],
            [
                'Laba Bersih',
                ($this->profit >= 0 ? '+' : '-') . ' Rp ' . number_format(abs($this->profit), 0, ',', '.'),
            ],
            [
                'Margin Keuntungan',
                number_format($this->profitMargin, 1) . '%',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Keterangan',
            'Nilai',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
        ];
    }
}