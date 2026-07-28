<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdController extends Controller
{
    public function index()
    {
        return view('admin.ads.index', [
            'ads' => Ad::orderBy('sort_order')->orderByDesc('created_at')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.ads.create', ['ad' => new Ad]);
    }

    public function store(Request $request)
    {
        $data = $this->validateAd($request);

        // New ads go to the end of the list.
        $data['sort_order'] = ((int) Ad::max('sort_order')) + 1;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
            $data['image_url'] = null;
        }

        Ad::create($data);

        return redirect()->route('admin.ads.index')->with('success', 'Ad created.');
    }

    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', ['ad' => $ad]);
    }

    public function update(Request $request, Ad $ad)
    {
        $data = $this->validateAd($request, $ad);

        if ($request->hasFile('image')) {
            if ($ad->image_path) {
                Storage::disk('public')->delete($ad->image_path);
            }
            $data['image_path'] = $request->file('image')->store('ads', 'public');
            $data['image_url'] = null;
        } elseif (! empty($data['image_url']) && $ad->image_path) {
            // Switching from an uploaded file to an external URL.
            Storage::disk('public')->delete($ad->image_path);
            $data['image_path'] = null;
        }

        $ad->update($data);

        return redirect()->route('admin.ads.index')->with('success', 'Ad updated.');
    }

    /**
     * Persist the drag-and-drop ordering from the admin ads index.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:ads,id'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['ids'] as $position => $id) {
                Ad::where('id', $id)->update(['sort_order' => $position]);
            }
        });

        return response()->noContent();
    }

    public function toggle(Ad $ad)
    {
        $ad->update(['is_active' => ! $ad->is_active]);

        return redirect()->route('admin.ads.index')
            ->with('success', $ad->is_active ? 'Ad activated.' : 'Ad deactivated.');
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted.');
    }

    private function validateAd(Request $request, ?Ad $ad = null): array
    {
        $hasExistingImage = (bool) ($ad?->image_path || $ad?->image_url);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => [
                Rule::requiredIf(fn () => ! $hasExistingImage && ! $request->filled('image_url')),
                'nullable',
                'image',
                'max:2048',
            ],
            'image_url' => [
                Rule::requiredIf(fn () => ! $request->hasFile('image') && ! $hasExistingImage),
                'nullable',
                'url',
                'max:2048',
            ],
            'link_url' => ['required', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        // Keep the existing external URL when the field is left blank on edit.
        if ($ad && ! $request->hasFile('image') && empty($data['image_url'])) {
            unset($data['image_url']);
        }

        unset($data['image']);

        return $data;
    }
}
