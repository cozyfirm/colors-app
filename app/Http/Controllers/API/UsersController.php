<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Http\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller{
    use ResponseTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Get basic user data
     */
    public function getUserData(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000', "Logged user", [
                'username' => Auth::guard()->user()->username,
                'email' => Auth::guard()->user()->email,
                'teams' => [
                    'status' => isset(Auth::guard()->user()->teamsRel),
                    'team' => Auth::guard()->user()->teamsRel->team ?? null,
                    'national_team' => Auth::guard()->user()->teamsRel->national_team ?? null
                ]
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('2001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Update basic user info
     */
    public function update(Request $request): JsonResponse{
        try{
            /* Check for username */
            if(!isset($request->username)) return $this->apiResponse('2012', __('Please, enter your username'));
            $user = User::where('username', $request->username)->first();

            if($user and ($user->username !== Auth::guard()->user()->username)) return $this->apiResponse('2013', __('This username has already been used'));

            /* Format date for SQL injection */
            $request['birth_date'] = Carbon::parse($request->birth_date)->format('Y-m-d');

            Auth::guard()->user()->update([
                'username' => $request->username,
                'birth_date' => $request->birth_date,
                'city' => $request->city
            ]);

            return $this->apiResponse('0000', "Success", [
                'username' => Auth::guard()->user()->username,
                'email' => Auth::guard()->user()->email,
                'birth_date' => Auth::guard()->user()->birth_date,
                'birth_date_f' => Carbon::parse(Auth::guard()->user()->birth_date)->format('d.m.Y'),
                'city' => Auth::guard()->user()->city,
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('2011', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
