<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model {
    use SoftDeletes;

    protected $fillable = [
        'sup_name', 'sup_phone', 'sup_email', 'sup_address', 'sup_note', 'sup_status',
    ];

}
