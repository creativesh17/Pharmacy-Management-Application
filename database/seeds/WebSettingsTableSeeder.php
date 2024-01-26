<?php

use App\WebSetting;
use Illuminate\Database\Seeder;

class WebSettingsTableSeeder extends Seeder
{
    public function run() {
        WebSetting::insert([
            [ 
                'web_title' => 'Tashan Pharmacy Store',
                'web_subtitle' => 'Best Pharmacy in Dhaka',
                'web_favicon' => 'favicon_1590705399.png',
                'web_footer_text' => 'CopyrightÃ‚Â© 2021 almasIT. All rights reserved.',
                'web_language' => 'English',
                'created_at' => now(),
            ],
        ]);
    }
}
