<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['name','unit_id','description'];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function stockUsage()
    {
        return $this->hasOne(StockUsage::class);
    }
}
