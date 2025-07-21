<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Dashboard accessed', [
            'user' => Auth::user(),
            'roles' => Auth::user()->roles->pluck('name'),
            'permissions' => Auth::user()->permissions->pluck('name')
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Widgets data
        $totalTransactions = Sale::whereBetween('sale_date', [$startDate, $endDate])->count();
        $totalSales = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_amount');
        $totalQty = SaleItem::whereHas('sale', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('sale_date', [$startDate, $endDate]);
        })->sum('qty');

        // Chart data - Monthly sales
        $monthlySales = Sale::selectRaw('MONTH(sale_date) as month, YEAR(sale_date) as year, SUM(total_amount) as total')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Chart data - Items sold
        $itemsSold = SaleItem::select('items.name', DB::raw('SUM(sale_items.qty) as total_qty'))
            ->join('items', 'sale_items.item_id', '=', 'items.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalTransactions',
            'totalSales',
            'totalQty',
            'monthlySales',
            'itemsSold',
            'startDate',
            'endDate'
        ));
    }
}
