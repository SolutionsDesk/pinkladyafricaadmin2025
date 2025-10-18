<?php

namespace App\Http\Controllers\Api\PinkLady;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PinkLady\PageResource;
use App\Models\Countries\Pages;

class PageDataController extends Controller
{
    public function show(string $country_code, string $slug)
    {
        $page = Pages::where('country_code', strtoupper($country_code))
            ->where('slug', $slug)
            //->where('status', 1) // Assuming a status column
            ->firstOrFail();

        return new PageResource($page);
    }

    public function index(string $country_code)
    {
        $pages = Pages::where('country_code', strtoupper($country_code))
            ->where('status', 1)
            //->orderBy('title')
            ->get(['title', 'slug']);

        return response()->json($pages);
    }
}
