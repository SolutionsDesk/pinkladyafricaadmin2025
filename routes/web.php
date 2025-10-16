<?php

use App\Http\Controllers\Competitions\CompetitionController;
use App\Http\Controllers\HealthyLiving\HealthyLivingController;
use App\Http\Controllers\Pages\AdminPageController;
use App\Http\Controllers\PLAuthentication\UserRoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Recipes\RecipeController;
use App\Http\Controllers\Settings\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/kenya-dashboard', function () {
    return '<h1>Welcome to the Kenya Dashboard</h1>';
})->middleware(['auth', 'verified', 'role:pl-kenya|webmaster'])->name('kenya.dashboard');

// Nigeria-specific route
Route::get('/nigeria-dashboard', function () {
    return '<h1>Welcome to the Nigeria Dashboard</h1>';
})->middleware(['auth', 'verified', 'role:pl-nigeria|webmaster'])->name('nigeria.dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:webmaster'])->group(function () {
    Route::get('users', [UserRoleController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserRoleController::class, 'create'])->name('users.create'); // <-- Add this
    Route::post('users', [UserRoleController::class, 'store'])->name('users.store');        // <-- Add this
    Route::get('users/{user}/edit', [UserRoleController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserRoleController::class, 'update'])->name('users.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // ... (your user management routes)

    // Group all page management routes under a country code prefix
    Route::prefix('admin/{country_code}')
        ->whereIn('country_code', ['ke', 'ng']) // Optional: Restricts to valid codes
        ->name('admin.country.')
        ->group(function () {
            Route::resource('pages', AdminPageController::class);
            // Healthy Living
            Route::resource('healthy-living', HealthyLivingController::class);
            // Recipes
            Route::resource('recipes', RecipeController::class);
            // Competitions
            Route::resource('competitions', CompetitionController::class);
            // Site Settings
            Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');
        });
});

require __DIR__.'/auth.php';
