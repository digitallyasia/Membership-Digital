<?php

namespace App\Http\Controllers;

use App\Benefit;
use App\Http\Requests\BenefitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BenefitController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('Benefits/Create');
    }

    public function store(BenefitRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $image = round(microtime(true) * 1000) . '.' . $request->file('image')->getClientOriginalExtension();
            $validated['image'] = $image;
            Storage::disk('images')->put(
                $image,
                file_get_contents($request->file('image'))
            );
        }
        Auth::guard('organization')->user()->benefits()->create($validated);

        return redirect()->route('organization.benefits')->with('success', 'Benefit created.');
    }

    public function edit(Request $request, Benefit $benefit)
    {
        return Inertia::render('Benefits/Edit', [
            'benefit' => $benefit,
        ]);
    }

    public function update(BenefitRequest $request, Benefit $benefit)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $image = round(microtime(true) * 1000) . '.' . $request->file('image')->getClientOriginalExtension();
            $validated['image'] = $image;
            Storage::disk('images')->put(
                $image,
                file_get_contents($request->file('image'))
            );
        }
        $benefit->update($validated);

        return redirect()->route('organization.benefits')->with('success', 'Benefit updated.');
    }

    public function destroy(Request $request, Benefit $benefit)
    {
        $benefit->delete();

        return redirect()->route('organization.benefits')->with('success', 'Benefit deleted.');
    }

    public function redeem(Request $request, Benefit $benefit)
    {
        $benefit->redemptions()->create([
            'user_id' => request()->user()->id
        ]);
        $benefit->increment('redemptions_count');
        return response('', 200);
    }
}
