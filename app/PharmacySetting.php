<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PharmacySetting extends Model {

    protected $fillable = [
        'ph_name', 'ph_phone', 'ph_email', 'ph_address', 'ph_about', 'ph_invoice_logo', 'ph_status',
    ];

    public function scopeActive($query) {
        return $query->where('ph_status', 1);
    }

}
