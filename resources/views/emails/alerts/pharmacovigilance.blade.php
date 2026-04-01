<x-mail::message>
# URGENT: Safety Recall Notice

Dear **{{ $customer->name }}**,

**Warning Message:**
We are contacting you because our records indicate you recently purchased a medication that is subject to an urgent Class I safety recall. 

**Medication Details:**
- **Product Name:** {{ $badMedication->name }}
- **Manufacturer:** Pharmacovigilance Alert System
- **Order ID:** {{ str_pad($order->id, 7, '0', STR_PAD_LEFT) }}
- **Purchase Date:** {{ \Carbon\Carbon::parse($order->purchase_date)->format('F j, Y') }}

**Affected Lot Number:**
### **{{ $badMedication->lot_number }}**

**Recommended Action:**
1. Stop using this medication **immediately**.
2. Return any unused pills to your nearest pharmacy for a full refund.
3. If you have experienced any adverse reactions, please contact your primary care physician immediately and report it replying to this email.

We apologize for this severe inconvenience. Patient safety is our rigorous priority.

Thanks,<br>
{{ config('app.name') }} Compliance Team
</x-mail::message>
