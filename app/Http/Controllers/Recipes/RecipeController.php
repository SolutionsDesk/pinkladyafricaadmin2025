<?php

namespace App\Http\Controllers\Recipes;

use App\Http\Controllers\Controller;
use App\Models\Recipes\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function index(string $country_code)
    {
        // The CountryScope automatically filters this for you!
        $recipes = Recipe::latest()->get();
        return view('admin.recipes.index', compact('recipes', 'country_code'));
    }

    public function create(string $country_code)
    {
        return view('admin.recipes.create', compact('country_code'));
    }

    public function store(Request $request, string $country_code)
    {
        $this->authorize('createForCountry', [Recipe::class, $country_code]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content.banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.serves' => 'nullable|string',
            'content.cooking_time' => 'nullable|string',
            'content.chef_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
            'content.chef_name' => 'nullable|string',
            'content.chef_website' => 'nullable|url',
            'content.recipe_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'content.recipe_video' => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:51200',
            'content.video_name' => 'nullable|string',
            'content.ingredients' => 'nullable|array',
            'content.ingredients.*.ingredient' => 'required|string',
            'content.ingredients.*.type' => 'required|in:ingredient,heading',
        ]);

        $content = $request->input('content', []);

        // Process all file uploads
        $fileFields = ['banner_image', 'chef_logo', 'recipe_pdf', 'recipe_video'];
        foreach ($fileFields as $field) {
            if ($request->hasFile("content.{$field}")) {
                $file = $request->file("content.{$field}");
                $path = $file->store('uploads/recipes', 'public');
                $content[$field] = ['path' => $path, 'name' => $file->getClientOriginalName()];
            }
        }

        Recipe::create([
            'title' => $validated['title'],
            'country_code' => strtoupper($country_code),
            'content' => $content,
        ]);

        return redirect()->route('admin.country.recipes.index', $country_code)
            ->with('success', 'Recipe created successfully.');
    }

    public function edit(string $country_code, Recipe $recipe)
    {
        return view('admin.recipes.edit', compact('recipe', 'country_code'));
    }

    public function update(Request $request, string $country_code, Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content.banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.serves' => 'nullable|string',
            'content.cooking_time' => 'nullable|string',
            'content.chef_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
            'content.chef_name' => 'nullable|string',
            'content.chef_website' => 'nullable|url',
            'content.recipe_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'content.recipe_video' => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:51200',
            'content.video_name' => 'nullable|string',
            'content.ingredients' => 'nullable|array',
            'content.ingredients.*.ingredient' => 'required|string',
            'content.ingredients.*.type' => 'required|in:ingredient,heading',
        ]);

        $newContent = $request->input('content', []);
        $originalContent = $recipe->content ?? [];

        // Process all file uploads on update
        $fileFields = ['banner_image', 'chef_logo', 'recipe_pdf', 'recipe_video'];
        foreach ($fileFields as $field) {
            if ($request->hasFile("content.{$field}")) {
                $file = $request->file("content.{$field}");
                $path = $file->store('uploads/recipes', 'public');
                if (isset($originalContent[$field]['path'])) {
                    Storage::disk('public')->delete($originalContent[$field]['path']);
                }
                $newContent[$field] = ['path' => $path, 'name' => $file->getClientOriginalName()];
            } else {
                $newContent[$field] = $originalContent[$field] ?? null;
            }
        }

        $recipe->update([
            'title' => $validated['title'],
            'content' => $newContent,
        ]);

        return redirect()->route('admin.country.recipes.index', $country_code)
            ->with('success', 'Recipe updated successfully.');
    }

    public function destroy(string $country_code, Recipe $recipe)
    {
        $this->authorize('delete', $recipe);

        // Clean up associated files
        $filePaths = [
            $recipe->content['banner_image']['path'] ?? null,
            $recipe->content['chef_logo']['path'] ?? null,
            $recipe->content['recipe_pdf']['path'] ?? null,
            $recipe->content['recipe_video']['path'] ?? null,
        ];
        Storage::disk('public')->delete(array_filter($filePaths));

        $recipe->delete();

        return redirect()->route('admin.country.recipes.index', $country_code)
            ->with('success', 'Recipe deleted successfully.');
    }
}
