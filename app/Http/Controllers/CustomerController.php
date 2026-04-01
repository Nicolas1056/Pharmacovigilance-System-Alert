<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function show($id) {
        $customer = Customer::with('orders.items.medication')->findOrFail($id);
        return response()->json($customer);
    }
}
