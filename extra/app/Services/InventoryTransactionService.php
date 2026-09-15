<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryTransactionService
{
    /**
     * Create an inventory transaction
     */
    public function createTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::find($data['product_id']);
            if (!$product) {
                throw new \Exception('Product not found');
            }

            $currentStock = $product->current_stock;
            $unitCost = $data['unit_cost'] ?? $product->average_cost ?? $product->purchase_cost ?? 0;

            $transaction = InventoryTransaction::create([
                'product_id' => $data['product_id'],
                'tenant_id' => $data['tenant_id'] ?? auth()->user()?->tenant_id,
                'user_id' => $data['user_id'] ?? auth()->id(),
                'transaction_type' => $data['transaction_type'],
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'opening_balance' => $currentStock,
                'stock_in' => $data['stock_in'] ?? 0,
                'stock_out' => $data['stock_out'] ?? 0,
                'closing_balance' => $currentStock + ($data['stock_in'] ?? 0) - ($data['stock_out'] ?? 0),
                'unit_id' => $data['unit_id'] ?? $product->unit_id,
                'unit_cost' => $unitCost,
                'total_value' => ($currentStock + ($data['stock_in'] ?? 0) - ($data['stock_out'] ?? 0)) * $unitCost,
                'batch_number' => $data['batch_number'] ?? null,
                'expiry_date' => $data['expiry_date'] ?? null,
                'manufacturing_date' => $data['manufacturing_date'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update product average cost on stock in
            if ($data['stock_in'] > 0 && $unitCost > 0) {
                $product->updateAverageCost($unitCost, $data['stock_in']);
            }

            return $transaction;
        });
    }

    /**
     * Stock in (increase inventory)
     */
    public function stockIn($productId, $quantity, $unitCost, $transactionType, $referenceType = null, $referenceId = null, $additionalData = [])
    {
        return $this->createTransaction([
            'product_id' => $productId,
            'stock_in' => $quantity,
            'unit_cost' => $unitCost,
            'transaction_type' => $transactionType,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            ...$additionalData,
        ]);
    }

    /**
     * Stock out (decrease inventory)
     */
    public function stockOut($productId, $quantity, $transactionType, $referenceType = null, $referenceId = null, $additionalData = [])
    {
        $product = Product::find($productId);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        $currentStock = $product->current_stock;

        // Check if sufficient stock
        if ($currentStock < $quantity) {
            throw new \Exception("Insufficient stock. Available: {$currentStock}, Required: {$quantity}");
        }

        return $this->createTransaction([
            'product_id' => $productId,
            'stock_out' => $quantity,
            'transaction_type' => $transactionType,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            ...$additionalData,
        ]);
    }

    /**
     * Create opening stock transaction
     */
    public function createOpeningStock($productId, $quantity, $unitCost, $additionalData = [])
    {
        return $this->stockIn($productId, $quantity, $unitCost, 'opening_stock', null, null, $additionalData);
    }

    /**
     * Create purchase stock in transaction
     */
    public function createPurchaseStockIn($productId, $quantity, $unitCost, $purchaseId, $additionalData = [])
    {
        return $this->stockIn($productId, $quantity, $unitCost, 'purchase', 'Purchase', $purchaseId, $additionalData);
    }

    /**
     * Create POS consumption transaction
     */
    public function createPOSConsumption($productId, $quantity, $orderId, $additionalData = [])
    {
        return $this->stockOut($productId, $quantity, 'pos_consumption', 'Order', $orderId, $additionalData);
    }

    /**
     * Create kitchen consumption transaction
     */
    public function createKitchenConsumption($productId, $quantity, $kitchenConsumptionId, $additionalData = [])
    {
        return $this->stockOut($productId, $quantity, 'kitchen_consumption', 'KitchenConsumption', $kitchenConsumptionId, $additionalData);
    }

    /**
     * Create wastage transaction
     */
    public function createWastage($productId, $quantity, $wastageId, $additionalData = [])
    {
        return $this->stockOut($productId, $quantity, 'wastage', 'Wastage', $wastageId, $additionalData);
    }

    /**
     * Create stock adjustment transaction
     */
    public function createStockAdjustment($productId, $quantity, $adjustmentType, $adjustmentId, $additionalData = [])
    {
        if ($adjustmentType === 'increase') {
            return $this->stockIn($productId, $quantity, 0, 'adjustment', 'StockAdjustment', $adjustmentId, $additionalData);
        } else {
            return $this->stockOut($productId, $quantity, 'adjustment', 'StockAdjustment', $adjustmentId, $additionalData);
        }
    }

    /**
     * Reverse a transaction (for order cancellation/refund)
     */
    public function reverseTransaction($originalTransaction, $reason = null)
    {
        return DB::transaction(function () use ($originalTransaction, $reason) {
            // Create reverse transaction
            $reverseTransaction = InventoryTransaction::create([
                'product_id' => $originalTransaction->product_id,
                'tenant_id' => $originalTransaction->tenant_id,
                'user_id' => auth()->id(),
                'transaction_type' => $originalTransaction->transaction_type . '_reversal',
                'reference_type' => $originalTransaction->reference_type,
                'reference_id' => $originalTransaction->reference_id,
                'opening_balance' => $originalTransaction->closing_balance,
                'stock_in' => $originalTransaction->stock_out,
                'stock_out' => $originalTransaction->stock_in,
                'closing_balance' => $originalTransaction->opening_balance,
                'unit_id' => $originalTransaction->unit_id,
                'unit_cost' => $originalTransaction->unit_cost,
                'total_value' => $originalTransaction->opening_balance * $originalTransaction->unit_cost,
                'batch_number' => $originalTransaction->batch_number,
                'expiry_date' => $originalTransaction->expiry_date,
                'manufacturing_date' => $originalTransaction->manufacturing_date,
                'notes' => $reason ?? 'Reversal of transaction #' . $originalTransaction->id,
            ]);

            return $reverseTransaction;
        });
    }

    /**
     * Get current stock for a product
     */
    public function getCurrentStock($productId)
    {
        $product = Product::find($productId);
        return $product ? $product->current_stock : 0;
    }

    /**
     * Get stock ledger for a product
     */
    public function getStockLedger($productId, $filters = [])
    {
        $query = InventoryTransaction::where('product_id', $productId);

        if (isset($filters['tenant_id'])) {
            $query->where('tenant_id', $filters['tenant_id']);
        }

        if (isset($filters['transaction_type'])) {
            $query->where('transaction_type', $filters['transaction_type']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Check if order has already deducted stock
     */
    public function hasOrderDeductedStock($orderId)
    {
        return InventoryTransaction::where('reference_type', 'Order')
            ->where('reference_id', $orderId)
            ->where('transaction_type', 'pos_consumption')
            ->exists();
    }
}
