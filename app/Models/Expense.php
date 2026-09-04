<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_number',
        'label_id',
        'employee_id',
        'supplier_id',
        'title',
        'description',
        'expense_date',
        'expense_date_bs',
        'payment_date',
        'payment_date_bs',
        'amount',
        'tax_percent',
        'tax_amount',
        'total_amount',
        'payment_method',
        'payment_reference',
        'status',
        'remarks',
        'entry_user_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    // Automatically generate expense number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $expense->expense_number = static::generateExpenseNumber();
            }
        });
    }

    public static function generateExpenseNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "EXP-{$year}{$month}-";
        
        $lastExpense = static::where('expense_number', 'like', $prefix . '%')
            ->orderBy('expense_number', 'desc')
            ->first();
        
        if ($lastExpense) {
            $lastNumber = intval(substr($lastExpense->expense_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Calculate tax amount from percentage
    public function calculateTaxAmount()
    {
        if ($this->amount && $this->tax_percent) {
            return round(($this->amount * $this->tax_percent) / 100, 2);
        }
        return 0;
    }


    // Get remaining amount to be paid
    public function getRemainingAmountAttribute()
    {
        return $this->getTotalAmountAttribute() - ($this->paid_amount ?? 0);
    }

    public function scopeActive($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->whereHas('payments', function($q) {
            $q->where('payment_status', 'paid');
        });
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }

        public function staff()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function entryUser()
    {
        return $this->belongsTo(User::class, 'entry_user_id');
    }
}