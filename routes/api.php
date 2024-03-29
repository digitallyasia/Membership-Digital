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
Route::post('/login-with-google', function (Request $request) {
    request()->validate([
        'user' => 'required',
        'fcm_token' => 'required',
    ]);

    try {
        $existingUser = User::where('email', $request->user['email'])->first();
    } catch (\Exception $e) {
        return response([
            'message' => 'Invalid Token',
        ], 404);
    }
    if ($existingUser) {
        $existingUser->update(['fcm_token' => request()->fcm_token]);
        return response([
            'token' => $existingUser->createToken('Application')->plainTextToken,
            'organizations' => $existingUser->organizations()->wherePivot('status', 'accepted')->pluck('organizations.id'),
        ], 200);
    } else {
        $newUser                  = new User;
        $newUser->name            = $request->user['name'];
        $newUser->email           = $request->user['email'];
        $newUser->email_verified_at = now();
        $newUser->google_id       = $request->user['id'];
        $newUser->profile_picture = $request->user['photo'];
        $newUser->fcm_token = request()->fcm_token;
        $newUser->save();
        return response([
            'token' => $newUser->createToken('Application')->plainTextToken,
            'organizations' => $newUser->organizations()->wherePivot('status', 'accepted')->pluck('organizations.id'),
        ], 200);
    }
});
Route::post('forget_password', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('me')->group(function () {
        Route::get('/', function () {
            return new UserResource(request()->user());
        });

        Route::put('/update', 'API\ProfileUpdateController');

        Route::get('/organizations', function () {
            return new OrganizationCollection(request()->user()->organizations()->paginate(50));
        });

        Route::get('/feeds', function () {
            $organizations = request()->user()->organizations()->wherePivot('status', 'accepted')->pluck('organizations.id');
            $announcements =  AnnouncementResource::collection(Announcement::whereIn('organization_id', $organizations)->limit(50)->get()->load('organization'));
            $benefits = BenefitResource::collection(Benefit::whereIn('organization_id', $organizations)->limit(50)->get()->load('organization'));
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
            $membership = $organization->membership(request()->user());
            $organization->status = $membership ? $membership->pivot->status : null;
            return new OrganizationResource($organization);
        });

        Route::get('/join', 'API\OrganizationJoinController');
        Route::get('/leave', 'OrganizationController@leave');

        Route::get('/announcements', function ($organization) {
            return $organization->isMember(request()->user())
                ? new AnnouncementCollection(Announcement::where('organization_id', $organization->id)->paginate(50))
                : response(['message' => 'You are not member of this organization'], 403);
        });

        Route::get('/benefits', function ($organization) {
            return $organization->isMember(request()->user())
                ? new BenefitCollection(Benefit::where('organization_id', $organization->id)->paginate(50))
                : response(['message' => 'You are not member of this organization'], 403);
        });
    });
    Route::get('benefits/{benefit}/redeem', 'BenefitController@redeem')->name('benefits.redeem');
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');
