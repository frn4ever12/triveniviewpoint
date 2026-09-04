<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PurchaseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Unit;
use App\Models\Supplier;
use App\Services\InventoryTransactionService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    use FileUploadTrait;

    protected $inventoryService;

    public function __construct(InventoryTransactionService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(PurchaseDataTable $dataTable)
    {
        return $dataTable->render('admin.purchase.index');
    }

    public function create()
    {
        $suppliers = Supplier::active()->get();
        $products = Product::all(['id', 'name']);
        return view('admin.purchase.create', compact('suppliers','products'));
    }

    public function store(PurchaseRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $purchase = Purchase::create([
                'title' => $validated['title'],
                'invoice_no' => $validated['invoice_no'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'purchase_date_bs' => $validated['purchase_date_bs'],
                'due_date' => $validated['due_date'] ?? null,
                'due_date_bs' => $validated['due_date_bs'] ?? null,
                'vendor_id' => $validated['vendor_id'] ?? null,
                'subtotal' => 0,
                'vat_percent' => $validated['vat_percent'] ?? 0,
                'vat_amount' => 0,
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'total_amount' => 0,
                'payment_status' => $validated['payment_status']??'pending',
            ]);

            $itemsPayload = [];
            $subtotal = 0;
            $totalVat = 0;
            foreach ($validated['items'] as $item) {
                $product = $item['product_id'];
                $quantity = (float)$item['quantity'];
                $unitRate = (float)$item['unit_rate'];
                $baseAmount = round($quantity * $unitRate, 2);

                $itemDiscountPercent = (float)($item['discount_percent'] ?? 0);
                $itemDiscountAmount = (float)($item['discount_amount'] ?? 0);
                if ($itemDiscountPercent > 0 && $itemDiscountAmount == 0) {
                    $itemDiscountAmount = round(($baseAmount * $itemDiscountPercent) / 100, 2);
                }
                $amountAfterDiscount = round($baseAmount - $itemDiscountAmount, 2);

                $vatPercent = (float)($item['vat_percent'] ?? 0);
                $vatAmount = round(($amountAfterDiscount * $vatPercent) / 100, 2);
                $totalAmount = round($amountAfterDiscount + $vatAmount, 2);

                $itemsPayload[] = [
                    'product_id' => $product,
                    'quantity' => $quantity,
                    'unit_rate' => $unitRate,
                    'base_amount' => $baseAmount,
                    'discount_percent' => $itemDiscountPercent,
                    'discount_amount' => $itemDiscountAmount,
                    'amount_after_discount' => $amountAfterDiscount,
                    'vat_percent' => $vatPercent,
                    'vat_amount' => $vatAmount,
                    'total_amount' => $totalAmount,
                    'add_to_inventory' => $item['add_to_inventory'] ?? true,
                    'batch_number' => $item['batch_number'] ?? null,
                    'expiry_date' => $item['expiry_date'] ?? null,
                ];
                $subtotal += $amountAfterDiscount;
                $totalVat += $vatAmount;
            }

            if (!empty($itemsPayload)) {
                $purchase->items()->createMany($itemsPayload);

                // Auto-update inventory for items marked to add to inventory
                foreach ($itemsPayload as $itemData) {
                    if ($itemData['add_to_inventory']) {
                        try {
                            $this->inventoryService->createPurchaseStockIn(
                                $itemData['product_id'],
                                $itemData['quantity'],
                                $itemData['unit_rate'],
                                $purchase->id,
                                [
                                    'batch_number' => $itemData['batch_number'] ?? null,
                                    'expiry_date' => $itemData['expiry_date'] ?? null,
                                ]
                            );
                        } catch (\Exception $e) {
                            \Log::error("Failed to add inventory for product {$itemData['product_id']}: " . $e->getMessage());
                        }
                    }
                }
            }

            $discountPercent = (float)($validated['discount_percent'] ?? 0);
            $discountAmount = (float)($validated['discount_amount'] ?? 0);
            if ($discountPercent > 0 && $discountAmount == 0) {
                $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
            }
            $purchaseVatPercent = (float)($validated['vat_percent'] ?? 0);
            if ($purchaseVatPercent > 0) {
                $totalVat = round(($subtotal * $purchaseVatPercent) / 100, 2);
            }
            $totalAmount = round($subtotal + $totalVat - $discountAmount, 2);

            $purchase->update([
                'subtotal' => $subtotal,
                'vat_amount' => $totalVat,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
            ]);

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Purchase created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create purchase. Please try again.');
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('items.product', 'supplier');
        return view('admin.purchase.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::active()->get();
        $products = Product::all();
        $purchase->load('items');
        $units = Unit::all();
        return view('admin.purchase.edit', compact('purchase', 'suppliers','products','units'));
    }

    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $purchase->update([
                'title' => $validated['title'],
                'invoice_no' => $validated['invoice_no'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'due_date' => $validated['due_date'] ?? null,
                'vendor_id' => $validated['vendor_id'] ?? null,
                'vat_percent' => $validated['vat_percent'] ?? $purchase->vat_percent,
                'discount_percent' => $validated['discount_percent'] ?? $purchase->discount_percent,
                'discount_amount' => $validated['discount_amount'] ?? $purchase->discount_amount,
            ]);

            $purchase->items()->delete();
            $itemsPayload = [];
            $subtotal = 0;
            $totalVat = 0;
            foreach ($validated['items'] as $item) {
                $product = $item['product_id'];
                $quantity = (float)$item['quantity'];
                $unitRate = (float)$item['unit_rate'];
                $baseAmount = round($quantity * $unitRate, 2);

                $itemDiscountPercent = (float)($item['discount_percent'] ?? 0);
                $itemDiscountAmount = (float)($item['discount_amount'] ?? 0);
                if ($itemDiscountPercent > 0 && $itemDiscountAmount == 0) {
                    $itemDiscountAmount = round(($baseAmount * $itemDiscountPercent) / 100, 2);
                }
                $amountAfterDiscount = round($baseAmount - $itemDiscountAmount, 2);

                $vatPercent = (float)($item['vat_percent'] ?? 0);
                $vatAmount = round(($amountAfterDiscount * $vatPercent) / 100, 2);
                $totalAmount = round($amountAfterDiscount + $vatAmount, 2);

                $itemsPayload[] = [
                    'product_id' => $product,
                    'quantity' => $quantity,
                    'unit_rate' => $unitRate,
                    'base_amount' => $baseAmount,
                    'discount_percent' => $itemDiscountPercent,
                    'discount_amount' => $itemDiscountAmount,
                    'amount_after_discount' => $amountAfterDiscount,
                    'vat_percent' => $vatPercent,
                    'vat_amount' => $vatAmount,
                    'total_amount' => $totalAmount,
                    'add_to_inventory' => $item['add_to_inventory'] ?? true,
                    'batch_number' => $item['batch_number'] ?? null,
                    'expiry_date' => $item['expiry_date'] ?? null,
                ];
                $subtotal += $amountAfterDiscount;
                $totalVat += $vatAmount;
            }

            if (!empty($itemsPayload)) {
                $purchase->items()->createMany($itemsPayload);
            }

            $discountPercent = (float)($validated['discount_percent'] ?? $purchase->discount_percent);
            $discountAmount = (float)($validated['discount_amount'] ?? $purchase->discount_amount);
            if ($discountPercent > 0 && ($discountAmount == 0)) {
                $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
            }
            $purchaseVatPercent = (float)($validated['vat_percent'] ?? $purchase->vat_percent);
            if ($purchaseVatPercent > 0) {
                $totalVat = round(($subtotal * $purchaseVatPercent) / 100, 2);
            }
            $totalAmount = round($subtotal + $totalVat - $discountAmount, 2);

            $purchase->update([
                'subtotal' => $subtotal,
                'vat_amount' => $totalVat,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
            ]);

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update purchase. Please try again.');
        }
    }

    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $purchase->items()->delete();
            $purchase->delete();
            DB::commit();
            return response()->json(['message' => 'Purchase deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete purchase.'], 500);
        }
    }

}
