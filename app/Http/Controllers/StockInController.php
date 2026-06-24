<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\StockInDetail;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $query = StockIn::with(['supplier', 'user']);

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('supplier', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockIns = $query->orderBy('date', 'desc')->paginate(10);
        return view('stock-ins.index', compact('stockIns'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('stock-ins.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Generate invoice number
            $invoiceNumber = 'SI-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

            // Create stock in header
            $stockIn = StockIn::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'invoice_number' => $invoiceNumber,
                'date' => $request->date,
                'total_price' => 0,
                'notes' => $request->notes,
            ]);

            $totalPrice = 0;

            // Create stock in details and update product stock
            foreach ($request->products as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $totalPrice += $subtotal;

                StockInDetail::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $subtotal,
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->increment('stock', $item['quantity']);
            }

            // Update total price
            $stockIn->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('stock-ins.index')
                ->with('success', 'Stok masuk berhasil dicatat! Invoice: ' . $invoiceNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(StockIn $stockIn)
    {
        $stockIn->load(['supplier', 'user', 'details.product']);
        return view('stock-ins.show', compact('stockIn'));
    }

    public function edit(StockIn $stockIn)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('stock-ins.edit', compact('stockIn', 'suppliers', 'products'));
    }

    public function update(Request $request, StockIn $stockIn)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Return old stock
            foreach ($stockIn->details as $detail) {
                $product = Product::find($detail->product_id);
                $product->decrement('stock', $detail->quantity);
            }

            // Delete old details
            $stockIn->details()->delete();

            $totalPrice = 0;

            // Create new details and update stock
            foreach ($request->products as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $totalPrice += $subtotal;

                StockInDetail::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $subtotal,
                ]);

                $product = Product::find($item['product_id']);
                $product->increment('stock', $item['quantity']);
            }

            $stockIn->update([
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total_price' => $totalPrice,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('stock-ins.index')
                ->with('success', 'Stok masuk berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(StockIn $stockIn)
    {
        DB::beginTransaction();

        try {
            // Return stock
            foreach ($stockIn->details as $detail) {
                $product = Product::find($detail->product_id);
                $product->decrement('stock', $detail->quantity);
            }

            $stockIn->details()->delete();
            $stockIn->delete();

            DB::commit();

            return redirect()->route('stock-ins.index')
                ->with('success', 'Stok masuk berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function print(StockIn $stockIn)
    {
        $stockIn->load(['supplier', 'user', 'details.product']);
        return view('stock-ins.print', compact('stockIn'));
    }
}