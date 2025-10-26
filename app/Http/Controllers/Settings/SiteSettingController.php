<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // <-- Make sure Str is imported

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
        $country_code_upper = strtoupper($country_code); // Added for consistency
        // Define base path
        $basePath = "uploads/{$country_code_upper}/settings";

        // Handle the footer logo upload
        if ($request->hasFile('settings.footer_details.footer_logo')) {
            $file = $request->file('settings.footer_details.footer_logo');

            $originalFilename = $file->getClientOriginalName();
            // Optional: Sanitize the filename
            $safeFilename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME), '-') . '.' . $file->getClientOriginalExtension();

            // Delete old logo if it exists, using the 'digitalocean' disk
            if (isset($originalSettings['footer_details']['footer_logo']['path'])) {
                // --- MODIFIED: Use 'digitalocean' disk ---
                Storage::disk('digitalocean')->delete($originalSettings['footer_details']['footer_logo']['path']);
            }

            // --- MODIFIED: Use 'digitalocean' disk and set visibility ---
            $path = Storage::disk('digitalocean')->putFileAs($basePath, $file, $safeFilename, 'public');
            // --- END OF CHANGE ---

            $newSettings['footer_details']['footer_logo'] = [
                'path' => $path,
                'name' => $originalFilename, // Store original name if needed
            ];
        } else {
            // Keep the existing logo if no new one is uploaded
            $newSettings['footer_details']['footer_logo'] = $originalSettings['footer_details']['footer_logo'] ?? null;
        }


        // Update the model with the merged settings
        $setting->update(['settings' => $newSettings]);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }


}
