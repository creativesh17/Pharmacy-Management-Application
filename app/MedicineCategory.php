<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicineCategory extends Model {
    use SoftDeletes;

    protected $fillable = [
        'cate_name', 'cate_note',
    ];
}
