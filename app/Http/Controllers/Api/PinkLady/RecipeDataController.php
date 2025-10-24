<?php

namespace App\Http\Controllers\Api\PinkLady;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PinkLady\RecipeResource;
use App\Http\Resources\Api\PinkLady\SingleRecipeResource; // We'll create this next
use App\Models\Recipes\Recipe; // Assuming this is your Recipe model namespace
use Illuminate\Http\Request;

class RecipeDataController extends Controller
{
    /**
     * Display the specified recipe resource.
     *
     * @param string $country_code
     * @param string $recipe_slug
     * @return SingleRecipeResource
     */
    public function show(string $country_code, string $recipe_slug)
    {
        // Find the recipe by country code and slug, ensuring it's published (status = 1)
        // Adjust 'status' column name if different
        $recipe = Recipe::where('country_code', strtoupper($country_code))
            ->where('slug', $recipe_slug)
            //->where('status', 1)
            ->firstOrFail(); // Automatically throws 404 if not found

        // Return the recipe formatted by the resource
        return new SingleRecipeResource($recipe);
    }

    // Optional: Add an index method if you need a paginated list for the main recipe page API
     public function index(string $country_code, Request $request)
     {
         $recipes = Recipe::where('country_code', strtoupper($country_code))
             //->where('status', 1)
             ->latest() // Or however you want to order them
             ->paginate($request->input('per_page', 12)); // Default 12 per page

         return RecipeResource::collection($recipes); // Use your existing RecipeResource for lists
     }
}
