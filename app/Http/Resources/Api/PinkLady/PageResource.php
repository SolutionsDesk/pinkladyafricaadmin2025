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

        // Global settings are always included. The model's scope handles country filtering.
        $data['global_settings'] = SiteSettingResource::make(SiteSetting::first());

        // Conditionally fetch data based on the page's template name.
        // The model scopes handle country filtering automatically based on the URL.
        switch ($this->template_name) {
            case 'recipes_listing':
                $data['recipes'] = RecipeResource::collection(Recipe::where('status', 1)->latest()->get());
                break;
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
