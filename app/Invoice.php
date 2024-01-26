<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {
    use SoftDeletes;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function invoiceDetails() {
        return $this->hasMany(InvoiceDetails::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }


}
