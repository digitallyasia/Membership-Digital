<?php

use App\Announcement;
use App\Benefit;
use App\Http\Resources\AnnouncementCollection;
use App\Http\Resources\BenefitCollection;
use App\Http\Resources\OrganizationCollection;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
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

Route::get('/pp', function (Request $request) {
    return response(Setting::get('pp'), 200);
});

Route::get('/tnc', function (Request $request) {
    return response(Setting::get('tnc'), 200);
});

Route::get('/faq', function (Request $request) {
    return response(Setting::get('faq'), 200);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'fcm_token' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    $user->update([
        'fcm_token' => $request->fcm_token
    ]);
    return response([
        'token' => $user->createToken('Application')->plainTextToken,
        'organizations' => $user->organizations()->wherePivot('status', 'accepted')->pluck('organizations.id'),
    ], 200);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('me')->group(function () {
        Route::get('/', function (Request $request) {
            return new UserResource($request->user());
        });

        Route::put('/update', 'API\ProfileUpdateController');

        Route::get('/organizations', function (Request $request) {
            return new OrganizationCollection($request->user()->organizations()->paginate());
        });
    });

    Route::prefix('organizations/{organization}')->group(function () {
        Route::get('/', function ($organization, Request $request) {
            if (!$organization->membership($request->user()))
                $organization->onlyOrganization = true;
            return new OrganizationResource($organization);
        });

        Route::get('/join', 'API\OrganizationJoinController');

        Route::get('/announcements', function ($organization, Request $request) {
            // return $organization->isMember($request->user())
            return new AnnouncementCollection(Announcement::where('organization_id', $organization->id)->paginate());
            // : response(['message' => 'You are not member of this organization'], 403);
        });

        Route::get('/benefits', function ($organization, Request $request) {
            // return $organization->isMember($request->user())
            return new BenefitCollection(Benefit::where('organization_id', $organization->id)->paginate());
            // : response(['message' => 'You are not member of this organization'], 403);
        });
    });
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');
