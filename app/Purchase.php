<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model {
    use SoftDeletes;

    protected $guarded = [];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseDetails() {
        return $this->hasMany(PurchaseDetails::class);
    }

    public function scopeActive($query) {
        return $query->where('purchase_status', 1);
    }
}
