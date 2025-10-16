<?php

namespace App\Http\Controllers\HealthyLiving;

use App\Http\Controllers\Controller;
use App\Models\HealthyLiving\HealthyLiving;
use Illuminate\Http\Request;

class HealthyLivingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $country_code)
    {
        // The CountryScope automatically filters this for you!
        $posts = HealthyLiving::latest()->get();
        return view('admin.healthy-living.index', compact('posts', 'country_code'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $country_code)
    {
        return view('admin.healthy-living.create', compact('country_code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $country_code)
    {
        $this->authorize('createForCountry', [HealthyLiving::class, $country_code]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
        ]);

        HealthyLiving::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'country_code' => strtoupper($country_code),
        ]);

        return redirect()->route('admin.country.healthy-living.index', $country_code)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $country_code, HealthyLiving $healthyLiving)
    {
        return view('admin.healthy-living.edit', compact('healthyLiving', 'country_code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $country_code, HealthyLiving $healthyLiving)
    {
        $this->authorize('update', $healthyLiving);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
        ]);

        $healthyLiving->update($validated);

        return redirect()->route('admin.country.healthy-living.index', $country_code)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $country_code, HealthyLiving $healthyLiving)
    {
        $this->authorize('delete', $healthyLiving);

        $healthyLiving->delete();

        return redirect()->route('admin.country.healthy-living.index', $country_code)
            ->with('success', 'Post deleted successfully.');
    }
}
