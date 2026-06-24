<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('user');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sales = $query->orderBy('date', 'desc')->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->where('stock', '>', 0)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('sales.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|json',
            'payment' => 'required|numeric|min:0',
        ]);

        $cart = json_decode($request->products, true);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $items = [];

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                
                if (!$product) {
                    throw new \Exception("Produk tidak ditemukan!");
                }

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi! (Stok: {$product->stock})");
                }

                $subtotal = $product->selling_price * $item['qty'];
                $totalPrice += $subtotal;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'price' => $product->selling_price,
                    'total' => $subtotal,
                ];
            }

            $discount = 0;
            $grandTotal = $totalPrice - $discount;
            $payment = $request->payment;
            $change = $payment - $grandTotal;

            if ($change < 0) {
                throw new \Exception('Pembayaran kurang!');
            }

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'date' => now(),
                'total_price' => $totalPrice,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'payment' => $payment,
                'change' => $change,
                'status' => 'completed',
            ]);

            $saleId = $sale->id;

            foreach ($items as $item) {
                SaleDetail::create([
                    'sale_id' => $saleId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);

                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('sales.receipt', $sale)
                ->with('success', 'Transaksi berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'details.product']);
        return view('sales.show', compact('sale'));
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['user', 'details.product']);
        return view('sales.receipt', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();

        try {
            foreach ($sale->details as $detail) {
                $product = Product::find($detail->product_id);
                $product->increment('stock', $detail->quantity);
            }

            $sale->details()->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Transaksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}