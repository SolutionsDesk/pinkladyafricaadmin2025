<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Countries\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    public function index(string $country_code)
    {
        $pages = Pages::where('country_code', strtoupper($country_code))
            ->latest()
            ->get();

        return view('admin.pages.index', compact('pages', 'country_code'));
    }

    public function create(string $country_code)
    {
        return view('admin.pages.create', compact('country_code'));
    }

    public function store(Request $request, string $country_code)
    {
        $this->authorize('createForCountry', [Pages::class, $country_code]);

        $rules = $this->getValidationRules($request->input('template_name'));
        $validated = $request->validate($rules);

        $content = $request->input('content', []);
        $country_code_upper = strtoupper($country_code); // Added for consistency
        // Define base paths
        $bannerPathBase = "uploads/{$country_code_upper}/banners";
        $pagePathBase = "uploads/{$country_code_upper}/pages";

        // Process Banner Repeater Images
        if (isset($content['banners'])) {
            foreach ($content['banners'] as $index => &$bannerData) {
                if ($request->hasFile("content.banners.{$index}.image_url")) {
                    $file = $request->file("content.banners.{$index}.image_url");

                    $originalFilename = $file->getClientOriginalName();
                    $safeFilename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME), '-') . '.' . $file->getClientOriginalExtension();

                    // --- MODIFIED: Use 'spaces' disk and set visibility ---
                    $path = Storage::disk('digitalocean')->putFileAs($bannerPathBase, $file, $safeFilename, 'public');
                    // --- End Change ---

                    $bannerData['image_url'] = ['path' => $path, 'name' => $originalFilename];
                }
            }
            // Address potential reference issue after loop
            unset($bannerData);
        }

        // Process all single image fields
        $singleImageFields = [
            'info_1_bg', 'info_2_bg', 'info_3_bg',
            'grown_image', 'image_1', 'image_2', 'image_3',
            'recipe_bg_image',
            'birth_bg_image', 'goodness_bg_image'
        ];
        foreach ($singleImageFields as $field) {
            if ($request->hasFile("content.{$field}")) {
                $file = $request->file("content.{$field}");

                $originalFilename = $file->getClientOriginalName();
                $safeFilename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME), '-') . '.' . $file->getClientOriginalExtension();

                // --- MODIFIED: Use 'spaces' disk and set visibility ---
                $path = Storage::disk('digitalocean')->putFileAs($pagePathBase, $file, $safeFilename, 'public');
                // --- End Change ---

                $content[$field] = ['path' => $path, 'name' => $originalFilename];
            }
        }

        Pages::create([
            'title' => $validated['title'],
            'country_code' => $country_code_upper,
            'template_name' => $validated['template_name'],
            'content' => $content,
        ]);

        return redirect()->route('admin.country.pages.index', $country_code)
            ->with('success', 'Page created successfully.');
    }

    public function edit(string $country_code, Pages $page)
    {
        return view('admin.pages.edit', compact('page', 'country_code'));
    }

    public function update(Request $request, string $country_code, Pages $page)
    {
        $this->authorize('update', $page);

        $rules = $this->getValidationRules($request->input('template_name'), true);
        $validated = $request->validate($rules);

        $newContent = $request->input('content', []);
        $originalContent = $page->content ?? [];
        $country_code_upper = strtoupper($country_code); // Added for consistency
        // Define base paths
        $bannerPathBase = "uploads/{$country_code_upper}/banners";
        $pagePathBase = "uploads/{$country_code_upper}/pages";

        // Process Banner Repeater Images
        if (isset($newContent['banners'])) {
            foreach ($newContent['banners'] as $index => &$bannerData) {
                if ($request->hasFile("content.banners.{$index}.image_url")) {
                    $file = $request->file("content.banners.{$index}.image_url");

                    $originalFilename = $file->getClientOriginalName();
                    $safeFilename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME), '-') . '.' . $file->getClientOriginalExtension();

                    // Delete old file if it exists
                    if (isset($originalContent['banners'][$index]['image_url']['path'])) {
                        // --- MODIFIED: Use 'spaces' disk ---
                        Storage::disk('digitalocean')->delete($originalContent['banners'][$index]['image_url']['path']);
                    }

                    // --- MODIFIED: Use 'spaces' disk and set visibility ---
                    $path = Storage::disk('digitalocean')->putFileAs($bannerPathBase, $file, $safeFilename, 'public');
                    // --- End Change ---

                    $bannerData['image_url'] = ['path' => $path, 'name' => $originalFilename];
                } else {
                    // Keep existing file data if no new file uploaded for this banner item
                    $bannerData['image_url'] = $originalContent['banners'][$index]['image_url'] ?? null;
                }
            }
            // Address potential reference issue after loop
            unset($bannerData);
        }

        // Process all single image fields on update
        $singleImageFields = [
            'info_1_bg', 'info_2_bg', 'info_3_bg',
            'grown_image', 'image_1', 'image_2', 'image_3',
            'recipe_bg_image',
            'birth_bg_image', 'goodness_bg_image'
        ];
        foreach ($singleImageFields as $field) {
            if ($request->hasFile("content.{$field}")) {
                $file = $request->file("content.{$field}");

                $originalFilename = $file->getClientOriginalName();
                $safeFilename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME), '-') . '.' . $file->getClientOriginalExtension();

                // Delete old file if it exists
                if (isset($originalContent[$field]['path'])) {
                    // --- MODIFIED: Use 'spaces' disk ---
                    Storage::disk('digitalocean')->delete($originalContent[$field]['path']);
                }

                // --- MODIFIED: Use 'spaces' disk and set visibility ---
                $path = Storage::disk('digitalocean')->putFileAs($pagePathBase, $file, $safeFilename, 'public');
                // --- End Change ---

                $newContent[$field] = ['path' => $path, 'name' => $originalFilename];
            } else {
                // Keep existing file data if no new file uploaded
                $newContent[$field] = $originalContent[$field] ?? null;
            }
        }

        // Cleanup orphaned banner images
        $originalImagePaths = collect($originalContent['banners'] ?? [])->pluck('image_url.path')->filter();
        $finalImagePaths = collect($newContent['banners'] ?? [])->pluck('image_url.path')->filter();
        $imagesToDelete = $originalImagePaths->diff($finalImagePaths);
        // --- MODIFIED: Use 'spaces' disk ---
        Storage::disk('digitalocean')->delete($imagesToDelete->all());

        // Cleanup orphaned single images
        $originalSingleImagePaths = collect($originalContent)->only($singleImageFields)->pluck('path')->filter();
        $finalSingleImagePaths = collect($newContent)->only($singleImageFields)->pluck('path')->filter();
        $singleImagesToDelete = $originalSingleImagePaths->diff($finalSingleImagePaths);
        // --- MODIFIED: Use 'spaces' disk ---
        Storage::disk('digitalocean')->delete($singleImagesToDelete->all());


        $page->update([
            'title' => $validated['title'],
            'template_name' => $validated['template_name'],
            'content' => $newContent,
        ]);

        return redirect()->route('admin.country.pages.index', $country_code)
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(string $country_code, Pages $page)
    {
        $this->authorize('delete', $page);
        $page->delete();
        return redirect()->route('admin.country.pages.index', $country_code)
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * Build and return validation rules based on the selected template.
     */
    private function getValidationRules(string $templateName, bool $isUpdate = false): array
    {
        $baseRules = [
            'title' => 'required|string|max:255',
            'template_name' => 'required|string',
            'content' => 'nullable|array',
        ];

        $templateRules = [];

        if ($templateName === 'home') {
            $imageRule = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $templateRules = [
                'content.banners' => 'nullable|array',
                'content.banners.*.image_url' => $imageRule,
                'content.banners.*.title' => 'nullable|string',
                'content.banners.*.description' => 'nullable|string',
                'content.info_1_bg' => $imageRule,
                'content.info_1_title' => 'nullable|string',
                'content.info_1_content' => 'nullable|string',
                'content.info_2_bg' => $imageRule,
                'content.info_2_title' => 'nullable|string',
                'content.info_2_content' => 'nullable|string',
                'content.info_3_bg' => $imageRule,
                'content.info_3_title' => 'nullable|string',
                'content.info_3_content' => 'nullable|string',
                'content.grown_image' => $imageRule,
                'content.grown_title' => 'nullable|string',
                'content.grown_content' => 'nullable|string',
                'content.image_1' => $imageRule,
                'content.image_2' => $imageRule,
                'content.image_3' => $imageRule,
                'content.recipe_bg_image' => $imageRule,
                'content.recipe_title' => 'nullable|string',
                'content.recipe_content' => 'nullable|string',
            ];
        }
        elseif ($templateName === 'story') {
            $imageRule = $isUpdate ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $templateRules = [
                'content.birth_bg_image' => $imageRule,
                'content.birth_title' => 'required|string',
                'content.birth_content' => 'required|string',
                'content.goodness_bg_image' => $imageRule,
                'content.goodness_title' => 'required|string',
                'content.goodness_content' => 'required|string',
                'content.goodness_list' => 'nullable|array',
                'content.goodness_list.*.text' => 'required_with:content.goodness_list|string',
            ];
        }

        return array_merge($baseRules, $templateRules);
    }
}
