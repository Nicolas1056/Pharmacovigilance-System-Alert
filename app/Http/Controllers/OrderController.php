<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function search(Request $request) {
        $request->validate([
            'lot' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $lotNumber = $request->lot;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        $query = Order::with(['customer', 'items.medication']);

        if (!empty($lotNumber)) {
            $query->whereHas('items.medication', function($q) use ($lotNumber){
                $q->where('lot_number', $lotNumber);
            });
        }

        $orders = $query->whereBetween('purchase_date', [$startDate->toDateString(), $endDate->toDateString()])
                        ->paginate(10);

        return response()->json($orders);
    }

    public function show($id) {
        $order = Order::with(['customer', 'items.medication'])->findOrFail($id);
        return response()->json($order);
    }
        
}
