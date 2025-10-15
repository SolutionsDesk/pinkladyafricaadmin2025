<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * This method runs before any other in the policy.
     * If a user has the 'webmaster' role, we grant them
     * full access immediately and stop checking other rules.
     */
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasRole('webmaster') ? true : null;
    }

    /**
     * Determine whether the user can view any models.
     * (All roles can view their country's pages, so this is true)
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * (The global scope already filters this, but this is an extra layer of security)
     */
    public function view(User $user, Page $page): bool
    {
        if ($user->hasRole('pl-kenya') && $page->country_code === 'KE') return true;
        if ($user->hasRole('pl-nigeria') && $page->country_code === 'NG') return true;
        return false;
    }

    /**
     * A custom method to check if a user can create a page for a specific country.
     * We'll use this in our PageController before a page object exists.
     */
    public function createForCountry(User $user, string $countryCode): bool
    {
        // Add this check for the webmaster role
        if ($user->hasRole('webmaster')) {
            return true;
        }

        // Keep the existing checks for country managers
        if (strtoupper($countryCode) === 'KE') return $user->hasRole('pl-kenya');
        if (strtoupper($countryCode) === 'NG') return $user->hasRole('pl-nigeria');

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Page $page): bool
    {
        // We can reuse our custom method for cleaner code
        return $this->createForCountry($user, $page->country_code);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Page $page): bool
    {
        return $this->update($user, $page); // Same logic as updating
    }
}
