<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;


class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'settings' => Setting::pluck('value', 'key')->toArray()
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->only(['site_name', 'contact_email', 'site_description', 'meta_keywords', 'meta_description']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Proses upload gambar
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $logoPath);
        }

        // Proses upload favicon
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $faviconPath);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui');
    }
}
