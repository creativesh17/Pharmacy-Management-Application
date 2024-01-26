<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model {
    use SoftDeletes;
    protected $guarded = [];

    public function category() {
        return $this->belongsTo(MedicineCategory::class, 'medicinecategory_id');
    }

    public function manufacturer() {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }
    
}
