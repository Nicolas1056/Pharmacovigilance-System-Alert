<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PharmacovigilanceAlert;

class AlertController extends Controller
{
    public function send(Request $request) {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'bulk' => 'nullable|boolean',
            'order_ids' => 'nullable|array',
            'lot_number' => 'nullable|string'
        ]);

        if($request->bulk) {
            $orders = Order::whereIn('id', $request->order_ids)->get();
            foreach ($orders as $order) {
                $this->triggerAlert($order->customer_id, $order->id, $request->lot_number);
            }
            return response()->json(['message' => 'Alertas masivas enviadas y registradas correctamente.']);
        }
        
        $this->triggerAlert($request->customer_id, $request->order_id, $request->lot_number);
        return response()->json(['message' => 'Alerta enviada y registrada correctamente']);
    }

    private function triggerAlert($customerId, $orderId, $lotNumber = null) {
        $alert = Alert::create([
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'sent_at' => now(),
        ]);

        $customer = Customer::find($customerId);
        $order = Order::with('items.medication')->find($orderId);

        // Identificamos el medicamento malo en la orden
        $badMedication = null;
        if ($lotNumber) {
            foreach($order->items as $item) {
                if($item->medication->lot_number === $lotNumber) {
                    $badMedication = $item->medication;
                    break;
                }
            }
        }

        // Si por alguna razón no hay, se manda un filler
        if (!$badMedication && $order->items->count() > 0) {
            $badMedication = $order->items->first()->medication;
        }

        // 1. Envía el Correo Real Maquetado
        if ($customer && $badMedication) {
            Mail::to($customer->email)->send(new PharmacovigilanceAlert($customer, $order, $badMedication));
        }

        // 2. Registramos la Acción
        Log::info("Alerta de Farmacovigilancia Enviada",[
            'alert_id' => $alert->id,
            'customer' => $customer->name,
            'email' => $customer->email,
            'lot_number' => $badMedication ? $badMedication->lot_number : 'N/A',
            'timestamp' => now(),
            'user_triggered' => auth()->user()->username
        ]);
    }
}
