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
            if ($membership->pivot->status === 'pending') {
                return response(['message' => 'You have a pending request to join this organization'], 400);
            } elseif ($membership->pivot->status === 'accepted') {
                return response(['message' => 'You are already member of this organization'], 400);
            } else {
                return response(['message' => 'You are blocked to join this organization'], 400);
            }
        } else {
            $organization->members()->attach($request->user()->id, [
                'status' => $organization->auto_join ? 'accepted' : 'pending',
                'membership_id' => $organization->members()->count() + 1
            ]);
            if (!$organization->auto_join)
                $organization->status = 'pending';
            return new OrganizationResource($organization);
        }
    }
}
