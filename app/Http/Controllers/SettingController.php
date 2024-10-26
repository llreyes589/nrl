<?php

namespace App\Http\Controllers;

use App\Models\Director;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    function index()
    {
        $settings = Setting::all();

        return view('settings.cert_list', compact('settings'));
    }

    function show($id)
    {
        $settings = Setting::find($id);
        $directors = Director::all();
        return view('settings.index', compact('settings', 'directors'));
    }

    function storeCertSettings(Request $request, $id)
    {
        $validated = $request->validate([
            'certificate_theme' => ['required', 'string', 'max:80'],
        ]);
        $settings = Setting::find($id);
        $request->merge(['updated_by' => \auth()->id()]);
        $settings->update($request->all());
        return redirect()->route('settings.index')->with('message', 'Settings has been saved.')->with('classname', 'alert-success');
    }
}
