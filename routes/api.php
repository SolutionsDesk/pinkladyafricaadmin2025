<?php

use App\Http\Controllers\Api\PinkLady\PageDataController;
use App\Http\Controllers\Api\PinkLady\RecipeDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- PINKLADY AFRICA API ROUTES ---
Route::middleware(['auth.apikey'])->prefix('v1/pinklady/{country_code}')->name('api.pinklady.')->group(function () {
    // Constrain the country_code to valid options
    Route::whereIn('country_code', ['ke', 'ng']);

    /**
     * Main dynamic route for all PinkLady pages.
     * e.g., GET /api/v1/pinklady/ke/pages/homepage
     */
    Route::get('/pages/{slug}', [PageDataController::class, 'show'])->name('pages.show');

    /**
     * Route for a list of all pages for a country.
     * e.g., GET /api/v1/pinklady/ng/pages
     */
    Route::get('/pages', [PageDataController::class, 'index'])->name('pages.index');


    /**
     * Recipe Routes (THE FIX)
     */
    // 2. ADD ROUTE FOR PAGINATED RECIPE INDEX
    Route::get('/recipes', [RecipeDataController::class, 'index'])->name('recipes.index');

    // 3. ADD ROUTE FOR SINGLE RECIPE
    Route::get('/recipes/{recipe_slug}', [RecipeDataController::class, 'show'])->name('recipes.show');
});
