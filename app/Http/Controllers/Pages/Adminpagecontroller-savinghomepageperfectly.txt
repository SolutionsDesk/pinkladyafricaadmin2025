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
            'content.banners.*.image_url' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_1_bg' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_2_bg' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_3_bg' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.grown_image' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.image_1' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.image_2' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.image_3' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.recipe_bg_image' => 'required_if:template_name,home|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.recipe_title' => 'required_if:template_name,home|string',
            'content.recipe_content' => 'required_if:template_name,home|string',
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

        // Process all single image fields
        $singleImageFields = ['info_1_bg', 'info_2_bg', 'info_3_bg', 'grown_image', 'image_1', 'image_2', 'image_3', 'recipe_bg_image'];
        foreach ($singleImageFields as $field) {
            if ($request->hasFile("content.{$field}")) {
                $file = $request->file("content.{$field}");
                $path = $file->store('uploads/homepage', 'public');
                $content[$field] = ['path' => $path, 'name' => $file->getClientOriginalName()];
            }
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
            'content.banners.*.image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_1_bg' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_2_bg' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.info_3_bg' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.grown_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.recipe_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $newContent = $request->input('content', []);
        $originalContent = $page->content ?? [];

        // Process Banner Repeater Images
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

        // Process all single image fields on update
        $singleImageFields = ['info_1_bg', 'info_2_bg', 'info_3_bg', 'grown_image', 'image_1', 'image_2', 'image_3', 'recipe_bg_image'];
        foreach ($singleImageFields as $field) {
            if ($request->hasFile("content.{$field}")) {
                $file = $request->file("content.{$field}");
                $path = $file->store('uploads/homepage', 'public');
                if (isset($originalContent[$field]['path'])) {
                    Storage::disk('public')->delete($originalContent[$field]['path']);
                }
                $newContent[$field] = ['path' => $path, 'name' => $file->getClientOriginalName()];
            } else {
                $newContent[$field] = $originalContent[$field] ?? null;
            }
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
