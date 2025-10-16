<?php

namespace App\Models\Settings;

use App\Models\Scopes\CountrySettingScope; // We will create this next
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * The "booted" method of the model.
     * This applies the CountrySettingScope to every query.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new CountrySettingScope);
    }
}
