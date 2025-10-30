<?php

namespace App\Http\Resources\Api\PinkLady;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $content = $this->content ?? [];

        // Helper function to safely get the *relative path* from the file data array
        $getPath = function ($fileData) {
            return (is_array($fileData) && isset($fileData['path'])) ? $fileData['path'] : null;
        };

      /*  return [
            'id' => $this->id, 'title' => $this->title, 'slug' => $this->slug,
            'country_code' => $this->country_code, 'content' => $this->content,
            'status' => $this->status, 'created_at' => $this->created_at,
        ];
      */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'country_code' => $this->country_code,
            'status' => $this->status, // Kept from your original
            'created_at' => $this->created_at, // Kept from your original
            'content' => $this->content,

            // --- Fields extracted from 'content' ---

            // Pass the relative path for the image
            'image_url' => $getPath($content['banner_image'] ?? null),

            'cooking_time' => $content['cooking_time'] ?? null,
            'serves' => $content['serves'] ?? null,
            'author' => $content['chef_name'] ?? 'Pink Lady®',

            // Pass the relative path for the PDF
            'pdf_url' => $getPath($content['recipe_pdf'] ?? null),

            // Pass the relative path for the video
            'video_url' => $getPath($content['video_url'] ?? null),

            // Pass the new categories array
            'categories' => $content['categories'] ?? [],
        ];
    }
}
