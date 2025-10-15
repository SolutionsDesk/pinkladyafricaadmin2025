<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CountryScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $countryCodeFromRoute = strtoupper(Request::route('country_code'));

            if ($user->hasRole('webmaster')) {
                if ($countryCodeFromRoute) {
                    $builder->where('country_code', $countryCodeFromRoute);
                }
                return; // If no country in route, webmaster sees all
            }

            $allowedCountries = [];
            if ($user->hasRole('pl-kenya')) { $allowedCountries[] = 'KE'; }
            if ($user->hasRole('pl-nigeria')) { $allowedCountries[] = 'NG'; }

            if ($countryCodeFromRoute && in_array($countryCodeFromRoute, $allowedCountries)) {
                $builder->where('country_code', $countryCodeFromRoute);
            } else {
                $builder->whereRaw('1 = 0'); // Deny access
            }
        }
    }
}
