<?php

namespace App\Http\Resources\Api\PinkLady;

use App\Models\Settings\SiteSetting;
use App\Models\Recipes\Recipe;
use App\Models\Competitions\Competition;
use App\Models\HealthyLiving\HealthyLiving;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\PinkLady\RecipeResource; // Ensure RecipeResource is imported
use Illuminate\Pagination\LengthAwarePaginator; // Import Paginator

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'title' => $this->title, 'slug' => $this->slug,
            'country_code' => $this->country_code, 'template_name' => $this->template_name,
            'content' => $this->content,
        ];

        // Global settings
        $data['global_settings'] = SiteSettingResource::make(SiteSetting::first());

        // Get the requested items per page, defaulting to 12
        $perPage = $request->input('per_page', 12);

        // --- STEP 1: Define a variable to hold our paginator ---
        $paginator = null;
        $paginatorKey = null; // To know what to name the "data" key

        // Conditionally fetch data based on the page's template name
        switch ($this->template_name) {

            // --- RECIPE CASES ---
            case 'recipes':
            case 'recipes_listing':
            case 'recipes-listing':
                $paginator = Recipe::latest()->paginate($perPage);
                $paginatorKey = 'recipes';
                break;

            case 'recipes-started':
                $paginator = Recipe::whereJsonContains('content->categories', 'starters')
                    ->latest()->paginate($perPage);
                $paginatorKey = 'recipes';
                break;
            case 'recipes-mains':
                $paginator = Recipe::whereJsonContains('content->categories', 'mains')
                    ->latest()->paginate($perPage);
                $paginatorKey = 'recipes';
                break;
            case 'recipes-snacks':
                $paginator = Recipe::whereJsonContains('content->categories', 'snacks')
                    ->latest()->paginate($perPage);
                $paginatorKey = 'recipes';
                break;
            case 'recipes-sweet':
                $paginator = Recipe::whereJsonContains('content->categories', 'sweet')
                    ->latest()->paginate($perPage);
                $paginatorKey = 'recipes';
                break;
            case 'recipes-video':
                $paginator = Recipe::whereJsonContains('content->categories', 'video')
                    ->latest()->paginate($perPage);
                $paginatorKey = 'recipes';
                break;

            // --- OTHER TEMPLATE CASES ---
            case 'competitions_listing':
                $paginator = Competition::latest()->paginate($perPage);
                $paginatorKey = 'competitions'; // Set the key
                break;
            case 'healthy_living_listing':
                $paginator = HealthyLiving::latest()->paginate($perPage);
                $paginatorKey = 'healthy_living_articles'; // Set the key
                break;
        }

        // --- STEP 2: Manually build the paginated response ---
        if ($paginator && $paginatorKey) {

            // 1. Create the resource collection (this just holds the data)
            // We must use the correct Resource based on the key
            if ($paginatorKey === 'recipes') {
                $collection = RecipeResource::collection($paginator);
            } elseif ($paginatorKey === 'competitions') {
                $collection = CompetitionResource::collection($paginator); // Assumes this resource exists
            } elseif ($paginatorKey === 'healthy_living_articles') {
                $collection = HealthyLivingResource::collection($paginator); // Assumes this resource exists
            } else {
                $collection = $paginator; // Fallback
            }

            // 2. Get the pagination data as an array
            $paginatedData = $paginator->toArray();

            // 3. Manually add the keys to our $data array
            $data[$paginatorKey] = $collection;                   // The actual items (recipes, etc.)
            $data['links'] = $paginatedData['links'];           // The pagination links
            $data['meta']  = [                                  // The pagination meta
                'current_page' => $paginatedData['current_page'],
                'from' => $paginatedData['from'],
                'last_page' => $paginatedData['last_page'],
                'path' => $paginatedData['path'],
                'per_page' => $paginatedData['per_page'],
                'to' => $paginatedData['to'],
                'total' => $paginatedData['total'],
            ];
        }

        return $data;
    }
}
