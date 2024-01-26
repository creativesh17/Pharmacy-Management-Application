<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDetails extends Model {
    use SoftDeletes;

    protected $guarded = [];
    
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function medicines() {
        return $this->hasMany(Medicine::class);
    }

}
