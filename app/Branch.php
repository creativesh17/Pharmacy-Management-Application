<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model {
    use SoftDeletes;

    protected $fillable = [
        'branch_title', 'branch_code', 'user_id', 'branch_phone', 'branch_address', 'branch_note', 'branch_start_date', 'branch_status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
