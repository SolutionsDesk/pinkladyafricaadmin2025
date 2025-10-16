<?php

namespace App\Policies;

use App\Models\Competitions\Competition;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompetitionPolicy
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

    public function update(User $user, Competition $competition): bool
    {
        return $this->createForCountry($user, $competition->country_code);
    }

    public function delete(User $user, Competition $competition): bool
    {
        return $this->update($user, $competition);
    }
}
