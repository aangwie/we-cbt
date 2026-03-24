<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class DateHelper
{
    public static function format($date, $formatOverride = null)
    {
        if (!$date) return '-';
        
        $setting = Cache::remember('app_setting_date_format', 60*60*24, function () {
            return Setting::first();
        });
        
        $format = $formatOverride ?: ($setting && $setting->date_format ? $setting->date_format : 'l, d F Y H:i');
        
        return Carbon::parse($date)->translatedFormat($format);
    }
}
