<?php

use App\Announcement;
use App\Benefit as AppBenefit;
use App\Benefit;
use App\Http\Resources\AnnouncementCollection;
use App\Http\Resources\BenefitCollection;
use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', 'API\MemberRegistrationController');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken('Application')->plainTextToken;
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', function (Request $request) {
        return new UserResource($request->user());
    });

    Route::put('/me/update', 'API\ProfileUpdateController');

    Route::prefix('organizations/{organization}/')->group(function () {
        Route::get('announcements', function (App\Organization $organization) {
            return new AnnouncementCollection(Announcement::where('organization_id', $organization->id)->paginate());
        });

        Route::get('benefits', function (App\Organization $organization) {
            return new BenefitCollection(Benefit::where('organization_id', $organization->id)->paginate());
        });
    });
});
