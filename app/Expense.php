<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model {
    use SoftDeletes;

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function branch() {
        return $this->belongsTo('App\Branch', 'branch_id');
    }

    public function expenseCategory() {
        return $this->belongsTo('App\ExpenseCategory', 'expensecategory_id');
    }

}
