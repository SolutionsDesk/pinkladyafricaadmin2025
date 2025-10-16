<?php

namespace App\Policies;

use App\Models\HealthyLiving\HealthyLiving;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HealthyLivingPolicy
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

    public function update(User $user, HealthyLiving $healthyLiving): bool
    {
        return $this->createForCountry($user, $healthyLiving->country_code);
    }

    public function delete(User $user, HealthyLiving $healthyLiving): bool
    {
        return $this->update($user, $healthyLiving);
    }
}
