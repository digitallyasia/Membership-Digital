<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UserUpdateRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = Storage::disk('images')->put(
                time() . $request->file('profile_picture')->getClientOriginalExtension(),
                $request->file('profile_picture')
            );
        }
        $request->user()->update($validated);
        return new UserResource($request->user());
    }
}
