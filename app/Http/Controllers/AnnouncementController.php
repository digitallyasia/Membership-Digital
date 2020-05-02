<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Http\Requests\AnnouncementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('Announcements/Create');
    }

    public function store(AnnouncementRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = Storage::disk('images')->put(
                time() . $request->file('image')->getClientOriginalExtension(),
                $request->file('image')
            );
        }
        Auth::guard('organization')->user()->announcements()->create($validated);

        return redirect()->route('organization.announcements')->with('success', 'Announcement created.');
    }

    public function edit(Request $request, Announcement $announcement)
    {
        return Inertia::render('Announcements/Edit', [
            'announcement' => $announcement,
        ]);
    }

    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = Storage::disk('images')->put(
                time() . $request->file('image')->getClientOriginalExtension(),
                $request->file('image')
            );
        }
        $announcement->update($validated);

        return redirect()->route('organization.announcements')->with('success', 'Announcement updated.');
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('organization.announcements')->with('success', 'Announcement deleted.');
    }
}
