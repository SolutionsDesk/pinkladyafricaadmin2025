<?php

namespace App\Policies;

use App\Models\Recipes\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecipePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasRole('webmaster') ? true : null;
    }

    public function createForCountry(User $user, string $countryCode): bool
    {
        if (strtoupper($countryCode) === 'KE') return $user->hasRole('pl-kenya');
        if (strtoupper($countryCode) === 'NG') return $user->hasRole('pl-nigeria');
        return false;
    }

    public function update(User $user, Recipe $recipe): bool
    {
        return $this->createForCountry($user, $recipe->country_code);
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $this->update($user, $recipe);
    }
}
