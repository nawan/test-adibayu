<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payments.index');
    }

    public function data(Request $request)
    {
        $query = Payment::with('sale')->orderBy('created_at', 'desc');

        if ($request->filled('date_filter')) {
            $query->whereDate('payment_date', $request->date_filter);
        }

        return DataTables::of($query)
            ->addColumn('action', function ($payment) {
                return '<a href="' . route('payments.show', $payment->id) . '" class="btn btn-sm btn-info">View</a> 
                        <a href="' . route('payments.edit', $payment->id) . '" class="btn btn-sm btn-warning">Edit</a> 
                        <form method="POST" action="' . route('payments.destroy', $payment->id) . '" class="d-inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>';
            })
            ->editColumn('amount', function ($payment) {
                return 'Rp ' . number_format($payment->amount, 0, ',', '.');
            })
            ->editColumn('payment_date', function ($payment) {
                return $payment->payment_date->format('d/m/Y');
            })
            ->addColumn('sale_code', function ($payment) {
                return $payment->sale->code;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $sales = Sale::whereIn('status', ['Belum Dibayar', 'Belum Dibayar Sepenuhnya'])->get();
        return view('payments.create', compact('sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $sale = Sale::findOrFail($request->sale_id);
        
        // Validate payment amount
        $remainingAmount = $sale->total_amount - $sale->paid_amount;
        if ($request->amount > $remainingAmount) {
            return back()->with('error', 'Payment amount cannot exceed remaining amount: Rp ' . number_format($remainingAmount, 0, ',', '.'));
        }

        $payment = Payment::create($request->all());

        // Update sale paid amount and status
        $sale->paid_amount += $request->amount;
        
        if ($sale->paid_amount >= $sale->total_amount) {
            $sale->status = 'Sudah Dibayar';
        } else {
            $sale->status = 'Belum Dibayar Sepenuhnya';
        }
        
        $sale->save();

        return redirect()->route('payments.index')->with('success', 'Payment created successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load('sale');
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $sales = Sale::whereIn('status', ['Belum Dibayar', 'Belum Dibayar Sepenuhnya', 'Sudah Dibayar'])->get();
        $payment->load('sale');
        return view('payments.edit', compact('payment', 'sales'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $sale = $payment->sale;
        
        // Revert previous payment amount
        $sale->paid_amount -= $payment->amount;
        
        // Validate new payment amount
        $remainingAmount = $sale->total_amount - $sale->paid_amount;
        if ($request->amount > $remainingAmount) {
            return back()->with('error', 'Payment amount cannot exceed remaining amount: Rp ' . number_format($remainingAmount, 0, ',', '.'));
        }

        $payment->update([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        // Update sale paid amount and status
        $sale->paid_amount += $request->amount;
        
        if ($sale->paid_amount >= $sale->total_amount) {
            $sale->status = 'Sudah Dibayar';
        } elseif ($sale->paid_amount > 0) {
            $sale->status = 'Belum Dibayar Sepenuhnya';
        } else {
            $sale->status = 'Belum Dibayar';
        }
        
        $sale->save();

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $sale = $payment->sale;
        
        // Revert payment amount from sale
        $sale->paid_amount -= $payment->amount;
        
        if ($sale->paid_amount <= 0) {
            $sale->status = 'Belum Dibayar';
            $sale->paid_amount = 0;
        } elseif ($sale->paid_amount < $sale->total_amount) {
            $sale->status = 'Belum Dibayar Sepenuhnya';
        }
        
        $sale->save();
        
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }

    public function getSaleDetails($saleId)
    {
        $sale = Sale::findOrFail($saleId);
        return response()->json([
            'total_amount' => $sale->total_amount,
            'paid_amount' => $sale->paid_amount,
            'remaining_amount' => $sale->remaining_amount,
        ]);
    }
}