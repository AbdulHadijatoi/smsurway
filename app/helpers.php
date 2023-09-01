<?php

use App\Models\Setting;

if(!function_exists('addreport')){
    
}
if(!function_exists('get_setting')){
    function get_setting($key)
    {
        // $setting = Setting::get($key)->first();
        $setting = Setting::where('key', $key)->first();
        // return $setting;
        if (is_null($setting)) {
            return [];
        } else {
            return $setting;
        }
    }
}

