<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index(){
        $settings = Settings::pluck('value','key')->toArray();

        if(!$settings){
            $settings = config('settings.default');
        }

        return $settings;
    }

    public function update(){
        $settings = request()->validate([
            'app_name' => ['required','string'],
            'date_format' => ['required','string'],
            'pagination_limit' => ['required','int','min:1','max:100']
        ]);

        foreach($settings as $key=>$value){
            Settings::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
           
        }

        Cache::flush('settings');
        return response()->json(['success' => true]);
    }
}
