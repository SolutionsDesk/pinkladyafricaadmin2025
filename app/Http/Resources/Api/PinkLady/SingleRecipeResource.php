<?php

namespace App\Http\Resources\Api\PinkLady;

use App\Models\Settings\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class SingleRecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Access the content JSON data, default to empty array if null
        $content = $this->content ?? [];


        // Build the data array
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'country_code' => $this->country_code,
            // 'status' => $this->status, // You might not need status on the frontend
            'created_at' => $this->created_at?->toIso8601String(), // Format date

            // --- Extract fields from the 'content' JSON column ---
            'image_url' => $content['banner_image'] ?? null,
            'cooking_time' => $content['cooking_time'] ?? null,
            'serves' => $content['serves'] ?? null,
            'author' => $content['chef_name'] ?? 'Pink Lady®', // Use chef_name, default if empty
            'chef_logo_url' => $content['chef_logo'] ?? null, // Added chef logo URL
            'chef_website' => $content['chef_website'] ?? null,
            'pdf_url' => $content['recipe_pdf'] ?? null,
            'video_url' => $content['recipe_video'] ?? null,
            'video_name' => $content['video_name'] ?? null, // Added video name

            // Ingredients are already an array within content
            'ingredients' => $content['ingredients'] ?? [],

            // Include the raw 'method' if it's stored directly in content
            // If method is stored elsewhere (e.g., separate column, relationship), adjust this
            'method' => $content['method'] ?? $this->method ?? null, // Example: Check content first, then a direct column

            // --- End of specific fields ---
        ];

        // Include global site settings
        $data['global_settings'] = SiteSettingResource::make(SiteSetting::first());

        return $data;
    }
}
