<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model {
    use SoftDeletes;

    protected $fillable = [
        'manu_name', 'manu_note',
    ];
}
