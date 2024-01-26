<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model {
    
    protected $fillable = [
        'web_title', 'web_subtitle', 'web_favicon', 'web_footer_text', 'web_language', 'web_status',
    ];

    public function scopeActive($query) {
        return $query->where('web_status', 1);
    }

}
