<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    function index(){
        $settings = Setting::find(1);
        return view('settings.index', compact('settings'));
    }

    function storeCertSettings(Request $request){
        $validated = $request->validate([
            'certificate_theme' => ['required', 'string', 'max:80'],
        ]);
        $settings = Setting::find(1);
        $request->merge(['updated_by' => \auth()->id()]);
        $settings->update($request->all());
        return redirect()->route('settings.index')->with('message', 'Settings has been saved.')->with('classname', 'alert-success');
    }
}
