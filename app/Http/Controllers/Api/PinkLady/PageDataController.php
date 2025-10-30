<?php

namespace App\Http\Controllers\Api\PinkLady;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PinkLady\PageResource;
use App\Models\Countries\Pages;
use Illuminate\Http\Request;

class PageDataController extends Controller
{
    // 2. INJECT Request
    public function show(Request $request, string $country_code, string $slug)
    {
        $page = Pages::where('country_code', strtoupper($country_code))
            ->where('slug', $slug)
            //->where('status', 1) // Assuming a status column
            ->firstOrFail();

        // The Request will now be available to the PageResource
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
