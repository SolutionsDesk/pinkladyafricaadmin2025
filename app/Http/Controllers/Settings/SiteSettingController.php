<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $country_code)
    {
        // Find the settings for this country or create a new empty one if it doesn't exist.
        // The CountrySettingScope automatically ensures the user can only see what they're allowed to.
        $setting = SiteSetting::firstOrCreate(['country_code' => strtoupper($country_code)]);

        return view('admin.settings.edit', compact('setting', 'country_code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $country_code)
    {
        $setting = SiteSetting::firstOrFail(); // The scope finds the correct one

        // Authorize the action using the policy
        $this->authorize('update', $setting);

        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.facebook_url' => 'nullable|url',
            'settings.instagram_url' => 'nullable|url',
            'settings.contact_person.name' => 'nullable|string',
            'settings.contact_person.cell' => 'nullable|string',
            'settings.contact_person.email' => 'nullable|email',
            'settings.contact_form.admin_email' => 'nullable|email',
            'settings.footer_details.footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'settings.footer_details.catch_line' => 'nullable|string',
            'settings.footer_details.cookie_consent' => 'nullable|string',
        ]);

        $newSettings = $request->input('settings', []);
        $originalSettings = $setting->settings ?? [];

        // Handle the footer logo upload
        if ($request->hasFile('settings.footer_details.footer_logo')) {
            $file = $request->file('settings.footer_details.footer_logo');
            $path = $file->store('uploads/settings', 'public');

            // Delete old logo if it exists
            if (isset($originalSettings['footer_details']['footer_logo']['path'])) {
                Storage::disk('public')->delete($originalSettings['footer_details']['footer_logo']['path']);
            }

            $newSettings['footer_details']['footer_logo'] = [
                'path' => $path,
                'name' => $file->getClientOriginalName(),
            ];
        } else {
            // Keep the existing logo if no new one is uploaded
            $newSettings['footer_details']['footer_logo'] = $originalSettings['footer_details']['footer_logo'] ?? null;
        }

        $setting->update(['settings' => $newSettings]);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
