<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }

    public function data(Request $request)
    {
        $query = Sale::with(['saleItems.item'])->orderBy('created_at', 'desc');

        if ($request->filled('date_filter')) {
            $query->whereDate('sale_date', $request->date_filter);
        }

        return DataTables::of($query)
            ->addColumn('action', function ($sale) {
                $actions = '<a href="' . route('sales.show', $sale->id) . '" class="btn btn-sm btn-info">View</a> ';

                if ($sale->status !== 'Sudah Dibayar') {
                    $actions .= '<a href="' . route('sales.edit', $sale->id) . '" class="btn btn-sm btn-warning">Edit</a> ';
                    $actions .= '<form method="POST" action="' . route('sales.destroy', $sale->id) . '" class="d-inline" onsubmit="return confirm(\'Are you sure?\')">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>';
                }

                return $actions;
            })
            ->addColumn('items_count', function ($sale) {
                return $sale->saleItems->count() . ' items';
            })
            ->addColumn('total_qty', function ($sale) {
                return $sale->saleItems->sum('qty');
            })
            ->editColumn('total_amount', function ($sale) {
                return 'Rp ' . number_format($sale->total_amount, 0, ',', '.');
            })
            ->editColumn('sale_date', function ($sale) {
                return $sale->sale_date->format('d/m/Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $items = Item::all();
        return view('sales.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['qty'] * $item['price'];
        }

        $sale = Sale::create([
            'user_id' => Auth::user()->id,
            'total_amount' => $totalAmount,
            'sale_date' => $request->sale_date,
        ]);

        foreach ($request->items as $item) {
            $totalPrice = $item['qty'] * $item['price'];
            SaleItem::create([
                'sale_id' => $sale->id,
                'item_id' => $item['item_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total_price' => $totalPrice,
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Sale created successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load(['saleItems.item']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if ($sale->status === 'Sudah Dibayar') {
            return redirect()->route('sales.index')->with('error', 'Cannot edit paid sale!');
        }

        $items = Item::all();
        $sale->load(['saleItems.item']);
        return view('sales.edit', compact('sale', 'items'));
    }

    public function update(Request $request, Sale $sale)
    {
        if ($sale->status === 'Sudah Dibayar') {
            return redirect()->route('sales.index')->with('error', 'Cannot edit paid sale!');
        }

        $request->validate([
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Delete existing sale items
        $sale->saleItems()->delete();

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['qty'] * $item['price'];
        }

        $sale->update([
            'total_amount' => $totalAmount,
            'sale_date' => $request->sale_date,
        ]);

        foreach ($request->items as $item) {
            $totalPrice = $item['qty'] * $item['price'];
            SaleItem::create([
                'sale_id' => $sale->id,
                'item_id' => $item['item_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total_price' => $totalPrice,
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        if ($sale->status === 'Sudah Dibayar') {
            return redirect()->route('sales.index')->with('error', 'Cannot delete paid sale!');
        }

        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully!');
    }
}
