<?php

namespace App\Http\Controllers\Competitions;

use App\Http\Controllers\Controller;
use App\Models\Competitions\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{
    public function index(string $country_code)
    {
        // The CountryScope automatically filters this for you!
        $competitions = Competition::latest()->get();
        return view('admin.competitions.index', compact('competitions', 'country_code'));
    }

    public function create(string $country_code)
    {
        return view('admin.competitions.create', compact('country_code'));
    }

    public function store(Request $request, string $country_code)
    {
        $this->authorize('createForCountry', [Competition::class, $country_code]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'content.bg_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.competition_name' => 'required|string',
            'content.body' => 'nullable|string',
        ]);

        $content = $request->input('content', []);

        if ($request->hasFile("content.bg_image")) {
            $file = $request->file("content.bg_image");
            $path = $file->store('uploads/competitions', 'public');
            $content['bg_image'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        }

        Competition::create([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'country_code' => strtoupper($country_code),
            'content' => $content,
        ]);

        return redirect()->route('admin.country.competitions.index', $country_code)
            ->with('success', 'Competition created successfully.');
    }

    public function edit(string $country_code, Competition $competition)
    {
        return view('admin.competitions.edit', compact('competition', 'country_code'));
    }

    public function update(Request $request, string $country_code, Competition $competition)
    {
        $this->authorize('update', $competition);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'content.bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content.competition_name' => 'required|string',
            'content.body' => 'nullable|string',
        ]);

        $newContent = $request->input('content', []);
        $originalContent = $competition->content ?? [];

        if ($request->hasFile("content.bg_image")) {
            $file = $request->file("content.bg_image");
            $path = $file->store('uploads/competitions', 'public');
            if (isset($originalContent['bg_image']['path'])) {
                Storage::disk('public')->delete($originalContent['bg_image']['path']);
            }
            $newContent['bg_image'] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        } else {
            $newContent['bg_image'] = $originalContent['bg_image'] ?? null;
        }

        $competition->update([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'content' => $newContent,
        ]);

        return redirect()->route('admin.country.competitions.index', $country_code)
            ->with('success', 'Competition updated successfully.');
    }

    public function destroy(string $country_code, Competition $competition)
    {
        $this->authorize('delete', $competition);

        if (isset($competition->content['bg_image']['path'])) {
            Storage::disk('public')->delete($competition->content['bg_image']['path']);
        }

        $competition->delete();

        return redirect()->route('admin.country.competitions.index', $country_code)
            ->with('success', 'Competition deleted successfully.');
    }
}
