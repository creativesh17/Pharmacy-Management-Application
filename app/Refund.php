<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model {
    use SoftDeletes;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function refundDetails() {
        return $this->hasMany(RefundDetails::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
