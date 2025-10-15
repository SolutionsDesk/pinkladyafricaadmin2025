<?php

namespace App\Models\Countries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Pages extends Model
{
    use HasFactory, HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title') // The field to generate the slug from
            ->saveSlugsTo('slug');      // The field to save the slug to
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'country_code',
        'template_name',
        'content',
    ];

    /**
     * The attributes that should be cast.
     * This automatically converts the 'content' field from a JSON string
     * in the database into a usable PHP array in your application.
     * @var array
     */
    protected $casts = [
        'content' => 'array',
    ];


}
