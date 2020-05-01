<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Organization;
use Illuminate\Http\Request;

class OrganizationJoinController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Organization $organization, Request $request)
    {
        if ($membership = $organization->membership($request->user())) {
            if (!$membership->pivot->status) {
                return response(['message' => 'You have a pending request to join this organization'], 400);
            } else {
                return response(['message' => 'You are already member of this organization'], 400);
            }
        } else {
            $organization->members()->attach($request->user()->id, [
                'status' => $organization->auto_join ? true : false
            ]);
            return new OrganizationResource($organization);
        }
    }
}
