<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PagesController extends Controller
{
    public function privacyPolicy(Request $request)
    {
        return Inertia::render('Organization/HTMLPage', [
            'html' => $request->user()->pp,
            'title' => 'Privacy Policy',
            'field' => 'pp',

        ]);
    }

    public function termsAndConditions(Request $request)
    {
        return Inertia::render('Organization/HTMLPage', [
            'html' => $request->user()->tnc,
            'title' => 'Term & Conditions',
            'field' => 'tnc',
        ]);
    }

    public function faq(Request $request)
    {
        return Inertia::render('Organization/HTMLPage', [
            'html' => $request->user()->faq,
            'title' => 'Frequently Asked Questions',
            'field' => 'faq',
        ]);
    }

    public function saveHtml(Request $request)
    {
        Auth::guard('organization')->user()->update([
            $request->field => $request->html
        ]);

        return back()->with('success', 'Successfully saved.');
    }
}
