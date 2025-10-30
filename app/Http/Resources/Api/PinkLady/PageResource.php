<?php

namespace App\Http\Resources\Api\PinkLady;

use App\Models\Settings\SiteSetting;
use App\Models\Recipes\Recipe;
use App\Models\Competitions\Competition;
use App\Models\HealthyLiving\HealthyLiving;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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

        $perPage = $request->input('per_page', 12);
        // Conditionally fetch data based on the page's template name
        switch ($this->template_name) {

            // --- RECIPE CASES ---
            case 'recipes_listing':
            case 'recipes-listing': // Handle both - or - _
                // Get ALL recipes
                $data['recipes'] = RecipeResource::collection(Recipe::latest()->paginate($perPage));
                break;
            case 'recipe-started':
                // Get recipes in 'mains' category
                $data['recipes'] = RecipeResource::collection(
                    Recipe::whereJsonContains('content->categories', 'starters')
                        ->latest()->paginate($perPage)
                );
                break;

            case 'recipes-mains':
                // Get recipes in 'mains' category
                $data['recipes'] = RecipeResource::collection(
                    Recipe::whereJsonContains('content->categories', 'mains')
                        ->latest()->paginate($perPage)
                );
                break;

            case 'recipes-snacks':
                // Get recipes in 'snacks' category
                $data['recipes'] = RecipeResource::collection(
                    Recipe::whereJsonContains('content->categories', 'snacks')
                        ->latest()->paginate($perPage)
                );
                break;

            case 'recipes-sweet':
                // Get recipes in 'snacks' category
                $data['recipes'] = RecipeResource::collection(
                    Recipe::whereJsonContains('content->categories', 'sweet')
                        ->latest()->paginate($perPage)
                );
                break;

            case 'recipes-video':
                // Get recipes in 'video' category
                $data['recipes'] = RecipeResource::collection(
                    Recipe::whereJsonContains('content->categories', 'video')
                        ->latest()->paginate($perPage)
                );
                break;

            // **NOTE:** You would need to add new cases here for 'recipes-starters', etc.

            // --- OTHER TEMPLATE CASES ---
            case 'competitions_listing':
                $data['competitions'] = CompetitionResource::collection(Competition::where('status', 1)->latest()->get());
                break;
            case 'healthy_living_listing':
                $data['healthy_living_articles'] = HealthyLivingResource::collection(HealthyLiving::where('status', 1)->latest()->get());
                break;
            // Add more cases for other templates like 'homepage'
        }

        return $data;
    }
}
