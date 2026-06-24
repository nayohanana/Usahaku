<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Statistik utama
        $salesToday = Sale::whereDate('date', $today)->sum('grand_total');
        $expensesToday = Expense::whereDate('date', $today)->sum('amount');
        
        // Keuntungan bulan ini (estimasi 30% dari total penjualan)
        $salesThisMonth = Sale::whereMonth('date', $month)->whereYear('date', $year)->sum('grand_total');
        $profitThisMonth = $salesThisMonth * 0.3;
        
        $totalProducts = Product::where('is_active', true)->count();

        // Produk terlaris
        $topProducts = Product::withCount(['saleDetails as total_sold' => function($query) {
            $query->select(DB::raw('sum(quantity)'));
        }])
        ->having('total_sold', '>', 0)
        ->orderBy('total_sold', 'desc')
        ->limit(5)
        ->get();

        // Stok menipis
        $lowStockProducts = Product::where('stock', '<=', DB::raw('min_stock'))
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Data grafik 12 bulan terakhir dengan format bulan + tahun
        $monthlySales = collect(range(11, 0))->map(function($monthOffset) {
            $date = Carbon::now()->subMonths($monthOffset);
            $sales = Sale::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('grand_total');
            
            return [
                'month' => $date->format('M Y'), // Format: Jan 2024
                'sales' => $sales,
                'profit' => $sales * 0.3
            ];
        });

        return view('dashboard', compact(
            'salesToday', 
            'expensesToday', 
            'profitThisMonth', 
            'totalProducts',
            'topProducts', 
            'lowStockProducts', 
            'monthlySales'
        ));
    }
}