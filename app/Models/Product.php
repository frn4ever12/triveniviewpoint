<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['name','unit_id','description','group','default_price','opening_stock_quantity','opening_stock_rate','multiple_unit'];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function stockUsage()
    {
        return $this->hasOne(StockUsage::class);
    }
}
