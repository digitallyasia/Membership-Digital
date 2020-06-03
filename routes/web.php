<?php

use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/')->name('organization.dashboard')->uses('DashboardController')->middleware('auth:organization');
Route::name('organization.')->namespace('Organization')->group(function () {
    Route::prefix('/organization')->group(function () {
        Auth::routes(['verify' => true]);
    });
    Route::middleware(['auth:organization'])->group(function () {
        Route::prefix('/members')->group(function () {
            Route::get('/active', 'OrganizationMembersController@activeMembers')->name('members.active');
            Route::get('/pending', 'OrganizationMembersController@pendingMembers')->name('members.pending');
            Route::get('/blocked', 'OrganizationMembersController@blockedMembers')->name('members.blocked');
            Route::post('/block', 'OrganizationMembersController@block')->name('members.block');
            Route::post('/unblock', 'OrganizationMembersController@unblock')->name('members.unblock');
            Route::post('/delete', 'OrganizationMembersController@delete')->name('members.delete');
            Route::post('/accept', 'OrganizationMembersController@acceptJoinRequest')->name('members.accept');
        });
        Route::get('/announcements', 'OrganiztionAnnouncementsController')->name('announcements');
        Route::get('/benefits', 'OrganiztionBenefitsController')->name('benefits');
        Route::get('/notifications', 'OrganiztionNotificationsController')->name('notifications');
        Route::get('/account/profile', 'OrganizationAccountController@profile')->name('profile');
        Route::get('/account/billing', 'OrganizationAccountController@billing')->name('billing');
    });
});

Route::middleware(['auth:organization'])->group(function () {
    Route::resource('organizations', 'OrganizationController')->except('index', 'create');

    Route::resource('announcements', 'AnnouncementController')->except('index', 'show');

    Route::resource('benefits', 'BenefitController')->except('index', 'show');

    Route::resource('notifications', 'NotificationController')->only('create', 'store', 'destroy');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/organizations', function () {
    return Organization::paginate();
});
