<?php

use Illuminate\Http\Request;
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
        \Auth::routes(['verify' => true]);
    });
    Route::middleware(['auth:organization'])->group(function () {
        Route::get('/members', 'OrganiztionMembersController')->name('members');
        Route::get('/announcements', 'OrganiztionAnnouncementsController')->name('announcements');
        Route::get('/benefits', 'OrganiztionBenefitsController')->name('benefits');
        Route::get('/notifications', 'OrganiztionNotificationsController')->name('notifications');
    });
});
Route::middleware(['auth:organization'])->group(function () {
    Route::resource('organizations', 'OrganizationController')->except('index', 'create');

    Route::resource('announcements', 'AnnouncementController')->except('index', 'show');

    Route::resource('benefits', 'BenefitController')->except('index', 'show');

    Route::resource('notifications', 'NotificationController')->only('create', 'store', 'destroy');
});
\Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
