<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRegistrationRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Support\Facades\Hash;

class MemberRegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(MemberRegistrationRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone_number' => $request['phone_number'],
            'fcm_token' => $request['fcm_token'],
        ]);

        $user->token = $user->createToken('Application')->plainTextToken;

        return new UserResource($user);
    }
}
