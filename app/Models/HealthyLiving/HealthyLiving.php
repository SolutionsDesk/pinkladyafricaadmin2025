<?php

namespace App\Models\HealthyLiving;

use App\Models\Scopes\CountryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class HealthyLiving extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * The "booted" method of the model.
     * We can reuse the exact same CountryScope here.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new CountryScope);
    }
}
