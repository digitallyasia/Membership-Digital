<?php

use App\Organization;
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
// Auth::routes([
//     'register' => false,
//     'confirm' => false,
//     'reset' => true,
//     'verify' => false
// ]);
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::post('/billplz/callback', 'BillPlzCallbackController')->name('billplz.callback');
Route::get('/billplz_redirect', 'BillPlzRedirectController')->name('billplz.redirect');
Route::name('organization.')->namespace('Organization')->group(function () {
    Route::prefix('/organization')->group(function () {
        Auth::routes(['verify' => false]);
    });
    Route::middleware(['auth:organization'])->group(function () {
        Route::post('/bill_generate', 'BillGenerateController')->name('bill.generate');
        Route::post('/bill_delete', 'BillDeleteController')->name('bill.delete');
        Route::get('/privacy-policy', 'PagesController@privacyPolicy')->name('policy');
        Route::get('/terms-and-conditions', 'PagesController@termsAndConditions')->name('tnc');
        Route::post('/save-page', 'PagesController@saveHtml')->name('save-page');
        Route::get('/faq', 'PagesController@faq')->name('faq');
        Route::prefix('/members')->group(function () {
            Route::get('/active', 'OrganizationMembersController@activeMembers')->name('members.active');
            Route::get('/pending', 'OrganizationMembersController@pendingMembers')->name('members.pending');
            Route::get('/blocked', 'OrganizationMembersController@blockedMembers')->name('members.blocked');
            Route::post('/block', 'OrganizationMembersController@block')->name('members.block');
            Route::post('/unblock', 'OrganizationMembersController@unblock')->name('members.unblock');
            Route::post('/delete', 'OrganizationMembersController@delete')->name('members.delete');
            Route::post('/accept', 'OrganizationMembersController@acceptJoinRequest')->name('members.accept');
        });
        Route::get('/announcements', 'OrganizationAnnouncementsController')->name('announcements');
        Route::get('/benefits', 'OrganizationBenefitsController')->name('benefits');
        Route::get('/notifications', 'OrganizationNotificationsController')->name('notifications');
        Route::get('/account/profile', 'OrganizationAccountController@profile')->name('profile');
        Route::get('/account/billing', 'OrganizationAccountController@billing')->name('billing');
        Route::delete('/account/delete', 'OrganizationAccountController@delete')->name('delete');
    });
});

Route::middleware(['auth:organization'])->group(function () {
    Route::resource('organizations', 'OrganizationController')->only('edit', 'update');
    Route::post('organizations/{organization}/update_card', 'OrganizationController@updateCard')->name('organizations.update_card');
    Route::post('organizations/{organization}/toggle-auto-join', 'OrganizationController@toggleAutoJoin')->name('organization.toggle_auto_join');

    Route::resource('announcements', 'AnnouncementController')->except('index', 'show');

    Route::resource('benefits', 'BenefitController')->except('index', 'show');

    Route::resource('notifications', 'NotificationController')->only('create', 'store', 'destroy');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/organizations', function () {
    return Organization::paginate();
});
