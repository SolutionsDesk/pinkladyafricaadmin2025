<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Countries\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class AdminPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $country_code)
    {
// The Global Scope automatically filters this based on the URL!
        $pages = Pages::latest()->get();
        return view('admin.pages.index', compact('pages', 'country_code'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $country_code)
    {
        return view('admin.pages.create', compact('country_code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $country_code)
    {
        $this->authorize('createForCountry', [Pages::class, $country_code]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'template_name' => 'required|string',
            'content' => 'nullable|array',
            // Banner Validation
            'content.banners' => 'nullable|array',
            'content.banners.*.image_url' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.banners.*.title' => 'nullable|string',
            'content.banners.*.description' => 'nullable|string',
            // Info Box 1 Validation
            'content.info_1_bg' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_1_title' => 'required_if:template_name,home|string',
            'content.info_1_content' => 'required_if:template_name,home|string',
            // Info Box 2 Validation
            'content.info_2_bg' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_2_title' => 'required_if:template_name,home|string',
            'content.info_2_content' => 'required_if:template_name,home|string',
        ]);

        $content = $request->input('content', []);

        // Process Banner Repeater Images
        if (isset($content['banners'])) {
            foreach ($content['banners'] as $index => &$bannerData) {
                if ($request->hasFile("content.banners.{$index}.image_url")) {
                    $file = $request->file("content.banners.{$index}.image_url");
                    $path = $file->store('uploads/banners', 'public');
                    $bannerData['image_url'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
                }
            }
        }

        // Process Homepage Info Box 1 Image
        if ($request->hasFile('content.info_1_bg')) {
            $file = $request->file('content.info_1_bg');
            $path = $file->store('uploads/infobox', 'public');
            $content['info_1_bg'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        }

        // Process Homepage Info Box 2 Image
        if ($request->hasFile('content.info_2_bg')) {
            $file = $request->file('content.info_2_bg');
            $path = $file->store('uploads/infobox', 'public');
            $content['info_2_bg'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        }

        Pages::create([
            'title' => $validated['title'],
            'country_code' => strtoupper($country_code),
            'template_name' => $validated['template_name'],
            'content' => $content,
        ]);

        return redirect()->route('admin.country.pages.index', $country_code)
            ->with('success', 'Page created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $country_code, Pages $page)
    {
        return view('admin.pages.edit', compact('page', 'country_code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $country_code, Pages $page)
    {
        $this->authorize('update', $page);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'template_name' => 'required|string',
            'content' => 'nullable|array',
            'content.banners.*.image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_1_bg' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_2_bg' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $newContent = $request->input('content', []);
        $originalContent = $page->content ?? [];

        // Process Banner Repeater Images on Update
        if (isset($newContent['banners'])) {
            foreach ($newContent['banners'] as $index => &$bannerData) {
                if ($request->hasFile("content.banners.{$index}.image_url")) {
                    $file = $request->file("content.banners.{$index}.image_url");
                    $path = $file->store('uploads/banners', 'public');
                    if (isset($originalContent['banners'][$index]['image_url']['path'])) {
                        Storage::disk('public')->delete($originalContent['banners'][$index]['image_url']['path']);
                    }
                    $bannerData['image_url'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
                } else {
                    $bannerData['image_url'] = $originalContent['banners'][$index]['image_url'] ?? null;
                }
            }
        }

        // Process Homepage Info Box 1 Image on Update
        if ($request->hasFile('content.info_1_bg')) {
            $file = $request->file('content.info_1_bg');
            $path = $file->store('uploads/infobox', 'public');
            if (isset($originalContent['info_1_bg']['path'])) {
                Storage::disk('public')->delete($originalContent['info_1_bg']['path']);
            }
            $newContent['info_1_bg'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        } else {
            $newContent['info_1_bg'] = $originalContent['info_1_bg'] ?? null;
        }

        // Process Homepage Info Box 2 Image on Update
        if ($request->hasFile('content.info_2_bg')) {
            $file = $request->file('content.info_2_bg');
            $path = $file->store('uploads/infobox', 'public');
            if (isset($originalContent['info_2_bg']['path'])) {
                Storage::disk('public')->delete($originalContent['info_2_bg']['path']);
            }
            $newContent['info_2_bg'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        } else {
            $newContent['info_2_bg'] = $originalContent['info_2_bg'] ?? null;
        }

        // Cleanup orphaned banner images
        $originalImagePaths = collect($originalContent['banners'] ?? [])->pluck('image_url.path')->filter();
        $finalImagePaths = collect($newContent['banners'] ?? [])->pluck('image_url.path')->filter();
        $imagesToDelete = $originalImagePaths->diff($finalImagePaths);
        Storage::disk('public')->delete($imagesToDelete->all());

        $page->update([
            'title' => $validated['title'],
            'template_name' => $validated['template_name'],
            'content' => $newContent,
        ]);

        return redirect()->route('admin.country.pages.index', $country_code)
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $country_code, Pages $page)
    {
// Authorize the action using the policy
        $this->authorize('delete', $page);

        $page->delete();

        return redirect()->route('admin.country.pages.index', $country_code)
            ->with('success', 'Page deleted successfully.');
    }
}
