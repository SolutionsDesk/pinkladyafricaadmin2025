<?php

namespace App\Policies;

use App\Models\Settings\SiteSetting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SiteSettingPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasRole('webmaster') ? true : null;
    }

    public function update(User $user, SiteSetting $siteSetting): bool
    {
        if ($user->hasRole('pl-kenya') && $siteSetting->country_code === 'KE') return true;
        if ($user->hasRole('pl-nigeria') && $siteSetting->country_code === 'NG') return true;
        return false;
    }
}
