<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetails extends Model {
    use SoftDeletes;

    protected $guarded = [];
    
    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }

    public function medicines() {
        return $this->hasMany(Medicine::class);
    }
}
