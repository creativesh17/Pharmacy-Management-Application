<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model {
    use SoftDeletes;
    
    protected $guarded = [];

    public $table = "staffs";

    // public function setNameAttribute($name) {
    //     $this->attributes['name'] = strtolower(trim($name));
    // }

    // public function getNameAttribute($name) {
    //     return ucwords($name);
    // }

    // public function setEmailAttribute($email) {
    //     $this->attributes['email'] = strtolower(trim($email));
    // }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
    
}
