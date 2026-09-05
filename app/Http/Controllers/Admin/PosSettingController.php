<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PosSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'vat_percent' => auth()->user()?->tenant?->vat_percent ?? 13,
            'service_charge_percent' => auth()->user()?->tenant?->service_charge_percent ?? 10,
            'default_payment_method' => auth()->user()?->tenant?->default_payment_method ?? 'cash',
            'auto_print_receipt' => auth()->user()?->tenant?->auto_print_receipt ?? true,
            'receipt_footer' => auth()->user()?->tenant?->receipt_footer ?? 'Thank you for dining with us!',
            'enable_kot' => auth()->user()?->tenant?->enable_kot ?? true,
            'enable_table_reservation' => auth()->user()?->tenant?->enable_table_reservation ?? true,
        ];

        return view('admin.pos-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'vat_percent' => 'required|numeric|min:0|max:100',
            'service_charge_percent' => 'required|numeric|min:0|max:100',
            'default_payment_method' => 'required|in:cash,card,esewa,khalti,fonepay',
            'auto_print_receipt' => 'boolean',
            'receipt_footer' => 'nullable|string|max:200',
            'enable_kot' => 'boolean',
            'enable_table_reservation' => 'boolean',
        ]);

        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $tenant->update([
                'vat_percent' => $validated['vat_percent'],
                'service_charge_percent' => $validated['service_charge_percent'],
                'default_payment_method' => $validated['default_payment_method'],
                'auto_print_receipt' => $request->has('auto_print_receipt'),
                'receipt_footer' => $validated['receipt_footer'],
                'enable_kot' => $request->has('enable_kot'),
                'enable_table_reservation' => $request->has('enable_table_reservation'),
            ]);
        }

        return redirect()->back()->with('success', 'POS settings updated successfully!');
    }
}
