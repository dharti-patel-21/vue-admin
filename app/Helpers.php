<?php
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

function setting($key){

    $settings = Cache::rememberForever('settings',function() {
        return Settings::pluck('value','key')->all();
    });
    
    if(!$settings){
        $settings = config('settings.default');
    }
    return $settings[$key] ?? false;
}
?>