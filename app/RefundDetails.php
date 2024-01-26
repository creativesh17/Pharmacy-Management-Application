<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefundDetails extends Model {
    use SoftDeletes;

    protected $guarded = [];

    public function refund() {
        return $this->belongsTo(Refund::class);
    }

    public function medicines() {
        return $this->hasMany(Medicine::class);
    }
}
