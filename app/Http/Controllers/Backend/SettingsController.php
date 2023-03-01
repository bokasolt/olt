<?php

namespace App\Http\Controllers\Backend;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController
{
    public function edit()
    {
        $options = Settings::all();

        return view('backend.settings.edit')
            ->withOptions($options);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'options' => 'required|array',
            'options.*' => 'required',
        ]);

        foreach ($data['options'] as $id => $val) {
            $settings = Settings::find($id);
            $settings->value = $val;
            $settings->update();
        }

        return redirect()->route('admin.dashboard')
            ->withFlashSuccess(__('The settings was successfully updated.'));
    }
}
