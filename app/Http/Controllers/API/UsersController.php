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
            return $this->apiResponse('0000', "User data", [
                'username' => Auth::guard()->user()->username,
                'email' => Auth::guard()->user()->email,
                'birth_date' => Auth::guard()->user()->birth_date,
                'birth_date_f' => Carbon::parse(Auth::guard()->user()->birth_date)->format('d.m.Y'),
                'city' => Auth::guard()->user()->city,
                'teams' => [
                    'status' => isset(Auth::guard()->user()->teamsRel),
                    'team' => Auth::guard()->user()->teamsRel->team ?? null,
                    'national_team' => Auth::guard()->user()->teamsRel->national_team ?? null
                ],
                's_not' => Auth::guard()->user()->s_not,
                's_loc' => Auth::guard()->user()->s_loc,
                's_b_date' => Auth::guard()->user()->s_b_date,
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

            return $this->getUserData($request);
        }catch (\Exception $e){
            return $this->apiResponse('2011', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /** ------------------------------------------------------------------------------------------------------------ **/
    /**
     *  Settings APIs:
     *      1. Language
     *      2. Notifications
     *      3. Show location
     *      4. Show date of birth
     */

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Change language used on mobile APP
     */
    public function setLanguage(Request $request): JsonResponse{

    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Update profile settings
     */
    public function profileSettings(Request $request) : JsonResponse{
        try{
            /* Transform to integer */
            $request['value'] = (int)($request->value);

            if($request->key != 's_not' and $request->key != 's_loc' and $request->key != 's_b_date'){ return $this->apiResponse('2032', __('Unknown key')); }
            if($request->value != 0 and $request->value != 1){ return $this->apiResponse('2033', __('Value not valid')); }

            Auth::guard()->user()->update([$request->key => $request->value]);

            return $this->getUserData($request);
        }catch (\Exception $e){
            return $this->apiResponse('2031', __('Error while processing your request. Please contact an administrator'));
        }
    }


    /* ToDo - Update image */

    /* ToDo - Select country */
}
