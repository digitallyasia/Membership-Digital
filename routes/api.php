<?php

use App\Announcement;
use App\Benefit;
use App\Http\Resources\Announcement as AnnouncementResource;
use App\Http\Resources\AnnouncementCollection;
use App\Http\Resources\Benefit as BenefitResource;
use App\Http\Resources\BenefitCollection;
use App\Http\Resources\OrganizationCollection;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use App\Rules\MatchOldPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
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

Route::get('/pp', function () {
    return response(Setting::get('pp'), 200);
});

Route::get('/tnc', function () {
    return response(Setting::get('tnc'), 200);
});

Route::get('/faq', function () {
    return response(Setting::get('faq'), 200);
});

Route::post('/login', function () {
    request()->validate([
        'email' => 'required|email',
        'password' => 'required',
        'fcm_token' => 'required',
    ]);

    $user = User::where('email', request()->email)->first();

    if (!$user || !Hash::check(request()->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    $user->update([
        'fcm_token' => request()->fcm_token
    ]);
    return response([
        'token' => $user->createToken('Application')->plainTextToken,
        'organizations' => $user->organizations()->wherePivot('status', 'accepted')->pluck('organizations.id'),
    ], 200);
});

Route::post('/forget_password', function () {
    request()->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', request()->email)->first();
    if ($user) {
        $newPassword = Str::random(8);

        request()->user()->update([
            'password' => Hash::make($newPassword)
        ]);

        return response([
            'message' => "New generated password successfully send to your email",
        ], 200);
    } else {
        return response([
            'message' => "The given email address is not associated with any account. Double-check your email address and try again.",
        ], 200);
    }
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('me')->group(function () {
        Route::get('/', function () {
            return new UserResource(request()->user());
        });

        Route::put('/update', 'API\ProfileUpdateController');

        Route::get('/organizations', function () {
            return new OrganizationCollection(request()->user()->organizations()->paginate());
        });

        Route::get('/feeds', function () {
            $organizations = request()->user()->organizations()->wherePivot('status', 'accepted')->pluck('organizations.id');
            $announcements =  AnnouncementResource::collection(Announcement::whereIn('organization_id', $organizations)->limit(50)->get());
            $benefits = BenefitResource::collection(Benefit::whereIn('organization_id', $organizations)->limit(50)->get());
            $collection = collect($benefits)->map(function ($benefit) {
                return (object) $benefit;
            });
            foreach ($announcements as $announcement) {
                $collection->push($announcement);
            };
            $collection->sortBy('created_at');
            return $collection;
        });
    });

    Route::post('/change_password', function () {
        request()->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        request()->user()->update([
            'password' => Hash::make(request()->new_password)
        ]);

        return response([
            'message' => "Password Successfully Updated",
        ], 200);
    });

    Route::prefix('organizations/{organization}')->group(function () {
        Route::get('/', function ($organization) {
            return new OrganizationResource($organization);
        });

        Route::get('/join', 'API\OrganizationJoinController');

        Route::get('/announcements', function ($organization) {
            return $organization->isMember(request()->user())
                ? new AnnouncementCollection(Announcement::where('organization_id', $organization->id)->paginate())
                : response(['message' => 'You are not member of this organization'], 403);
        });

        Route::get('/benefits', function ($organization) {
            return $organization->isMember(request()->user())
                ? new BenefitCollection(Benefit::where('organization_id', $organization->id)->paginate())
                : response(['message' => 'You are not member of this organization'], 403);
        });
    });
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');
