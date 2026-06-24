<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Info toko (ambil dari config atau database)
        $storeName = env('APP_NAME', 'UsahaKu');
        $storeAddress = 'Jl. Sudirman No. 123, Jakarta';
        $storePhone = '0812-3456-7890';
        $storeEmail = 'info@usahaku.com';

        // Info aplikasi
        $appVersion = '1.0.0';
        $appDeveloper = 'UsahaKu Team';
        $appWebsite = 'https://usahaku.com';

        // Statistik database
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalSales = Sale::count();
        $totalExpenses = Expense::count();
        $dbSize = $this->getDatabaseSize();

        // Log aktivitas terakhir (5 data)
        $recentActivities = $this->getRecentActivities();

        return view('settings.index', compact(
            'storeName', 'storeAddress', 'storePhone', 'storeEmail',
            'appVersion', 'appDeveloper', 'appWebsite',
            'totalUsers', 'totalProducts', 'totalSales', 'totalExpenses',
            'dbSize', 'recentActivities'
        ));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
        ]);

        // Update .env atau database
        $this->updateEnv('APP_NAME', $request->store_name);

        return redirect()->route('settings.index')
            ->with('success', 'Profil toko berhasil diupdate!');
    }

    public function backup()
    {
        try {
            $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backup/' . $backupFile);

            // Pastikan folder backup ada
            if (!is_dir(storage_path('app/backup'))) {
                mkdir(storage_path('app/backup'), 0755, true);
            }

            // Backup database (simulasi - untuk production pakai mysqldump)
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_HOST'),
                env('DB_DATABASE'),
                $path
            );

            // Jalankan command
            exec($command);

            return response()->download($path)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal backup database: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');

        return redirect()->back()
            ->with('success', 'Cache berhasil dibersihkan!');
    }

    private function getDatabaseSize()
    {
        try {
            $database = env('DB_DATABASE');
            $result = DB::select("SELECT SUM(data_length + index_length) AS size 
                                  FROM information_schema.TABLES 
                                  WHERE table_schema = ?", [$database]);
            
            $size = $result[0]->size ?? 0;
            
            if ($size < 1024) {
                return number_format($size, 2) . ' B';
            } elseif ($size < 1048576) {
                return number_format($size / 1024, 2) . ' KB';
            } elseif ($size < 1073741824) {
                return number_format($size / 1048576, 2) . ' MB';
            } else {
                return number_format($size / 1073741824, 2) . ' GB';
            }
        } catch (\Exception $e) {
            return '0 KB';
        }
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Ambil 3 transaksi terakhir
        $sales = Sale::with('user')->orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($sales as $sale) {
            $activities[] = [
                'user' => $sale->user->name,
                'action' => 'Transaksi penjualan ' . $sale->invoice_number,
                'time' => $sale->created_at->diffForHumans(),
            ];
        }

        // Ambil 2 pengeluaran terakhir
        $expenses = Expense::with('user')->orderBy('created_at', 'desc')->limit(2)->get();
        foreach ($expenses as $expense) {
            $activities[] = [
                'user' => $expense->user->name,
                'action' => 'Pengeluaran: ' . $expense->description,
                'time' => $expense->created_at->diffForHumans(),
            ];
        }

        // Sort by time
        usort($activities, function($a, $b) {
            return strtotime($a['time']) < strtotime($b['time']);
        });

        return array_slice($activities, 0, 5);
    }

    private function updateEnv($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            file_put_contents($path, $content);
        }
    }
}