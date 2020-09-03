<?php

namespace Settings\Http\Controllers;

use Settings\UserSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = UserSettings::where('user_id', auth()->user()->id)->first();
        return view('settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = UserSettings::where('user_id', auth()->user()->id)->first();
        $settings->timezone = $request->input('timezone');

        if ($settings->save()) {
            return redirect(route('settings.index'))->with('success_message', 'Success! Settings updated.');
        } else {
            return redirect(route('settings.index'))->with('error_message', 'Error! Unable to update settings.');
        }
    }
}