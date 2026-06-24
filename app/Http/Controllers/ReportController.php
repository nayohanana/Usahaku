<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use App\Exports\ExpensesExport;
use App\Exports\ProfitExport;

class ReportController extends Controller
{
    public function index()
    {
        // Data untuk dashboard laporan
        $totalSales = Sale::sum('grand_total');
        $totalExpenses = Expense::sum('amount');
        $totalProfit = $totalSales - $totalExpenses;
        $totalProducts = Product::count();
        $totalTransactions = Sale::count();

        // Grafik penjualan 7 hari terakhir
        $weeklySales = collect(range(6, 0))->map(function($day) {
            $date = now()->subDays($day);
            return [
                'date' => $date->format('d M'),
                'sales' => Sale::whereDate('date', $date)->sum('grand_total'),
                'expenses' => Expense::whereDate('date', $date)->sum('amount'),
            ];
        });

        return view('reports.index', compact(
            'totalSales', 'totalExpenses', 'totalProfit', 
            'totalProducts', 'totalTransactions', 'weeklySales'
        ));
    }

    public function sales(Request $request)
    {
        $query = Sale::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sales = $query->orderBy('date', 'desc')->get();
        $totalSales = $query->sum('grand_total');
        $totalTransactions = $query->count();

        return view('reports.sales', compact('sales', 'totalSales', 'totalTransactions'));
    }

    public function expenses(Request $request)
    {
        $query = Expense::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('date', 'desc')->get();
        $totalExpenses = $query->sum('amount');
        $totalItems = $query->count();

        // Group by category
        $categorySummary = Expense::select('category', DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->get();

        return view('reports.expenses', compact('expenses', 'totalExpenses', 'totalItems', 'categorySummary'));
    }

    public function profit(Request $request)
    {
        $querySales = Sale::query();
        $queryExpenses = Expense::query();

        if ($request->filled('date_from')) {
            $querySales->whereDate('date', '>=', $request->date_from);
            $queryExpenses->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $querySales->whereDate('date', '<=', $request->date_to);
            $queryExpenses->whereDate('date', '<=', $request->date_to);
        }

        $totalSales = $querySales->sum('grand_total');
        $totalExpenses = $queryExpenses->sum('amount');
        $profit = $totalSales - $totalExpenses;
        $profitMargin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;

        return view('reports.profit', compact('totalSales', 'totalExpenses', 'profit', 'profitMargin'));
    }

    // ============================================
    // 📊 EXPORT PDF
    // ============================================
    
    public function exportSalesPdf(Request $request)
    {
        $query = Sale::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sales = $query->orderBy('date', 'desc')->get();
        $totalSales = $query->sum('grand_total');
        $totalTransactions = $query->count();

        $pdf = Pdf::loadView('reports.exports.sales-pdf', compact('sales', 'totalSales', 'totalTransactions'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    public function exportExpensesPdf(Request $request)
    {
        $query = Expense::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('date', 'desc')->get();
        $totalExpenses = $query->sum('amount');

        $pdf = Pdf::loadView('reports.exports.expenses-pdf', compact('expenses', 'totalExpenses'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-pengeluaran-' . date('Y-m-d') . '.pdf');
    }

    public function exportProfitPdf(Request $request)
    {
        $querySales = Sale::query();
        $queryExpenses = Expense::query();

        if ($request->filled('date_from')) {
            $querySales->whereDate('date', '>=', $request->date_from);
            $queryExpenses->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $querySales->whereDate('date', '<=', $request->date_to);
            $queryExpenses->whereDate('date', '<=', $request->date_to);
        }

        $totalSales = $querySales->sum('grand_total');
        $totalExpenses = $queryExpenses->sum('amount');
        $profit = $totalSales - $totalExpenses;
        $profitMargin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;

        $pdf = Pdf::loadView('reports.exports.profit-pdf', compact('totalSales', 'totalExpenses', 'profit', 'profitMargin'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-laba-rugi-' . date('Y-m-d') . '.pdf');
    }

    // ============================================
    // 📊 EXPORT EXCEL
    // ============================================
    
    public function exportSalesExcel(Request $request)
    {
        $query = Sale::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sales = $query->orderBy('date', 'desc')->get();
        
        return Excel::download(new SalesExport($sales), 'laporan-penjualan-' . date('Y-m-d') . '.xlsx');
    }

    public function exportExpensesExcel(Request $request)
    {
        $query = Expense::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('date', 'desc')->get();
        
        return Excel::download(new ExpensesExport($expenses), 'laporan-pengeluaran-' . date('Y-m-d') . '.xlsx');
    }

    public function exportProfitExcel(Request $request)
    {
        $querySales = Sale::query();
        $queryExpenses = Expense::query();

        if ($request->filled('date_from')) {
            $querySales->whereDate('date', '>=', $request->date_from);
            $queryExpenses->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $querySales->whereDate('date', '<=', $request->date_to);
            $queryExpenses->whereDate('date', '<=', $request->date_to);
        }

        $totalSales = $querySales->sum('grand_total');
        $totalExpenses = $queryExpenses->sum('amount');
        $profit = $totalSales - $totalExpenses;
        $profitMargin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;

        return Excel::download(new ProfitExport($totalSales, $totalExpenses, $profit, $profitMargin), 'laporan-laba-rugi-' . date('Y-m-d') . '.xlsx');
    }
}