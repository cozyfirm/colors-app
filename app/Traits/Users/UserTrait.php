<?php

namespace App\Traits\Users;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

trait UserTrait{

    /*
     *  Check if email has been used. Return values:
     *
     *      0. Email has been NOT used
     *      1. Email has been used
     */
    protected function checkForAnEmail($email, $user_id = null){
        if($user_id){
            return User::where('email', $email)->where('id', '!=', $user_id)->count();
        }else{
            return User::where('email', $email)->count();
        }
    }

    /*
     *  Check for an phone number length. Return values:
     *      false: Number is not OK
     *      true: Number length is OK
     */
    protected function phoneLengthOK($phone){
        return (strlen($phone) >= 8 and strlen($phone) <= 10);
    }

    /*
     *  Validate and update user token for special characters
     */
    protected function generateHash($email): string{
        $hash = hash('sha256', $email. '+'. time());
        $hash = str_replace('/', '-', $hash);

        return str_replace('&', '-', $hash);
    }

    /*
     *  Get basic user data
     */
    public function getUserData($code = '2001', $message = 'User data'): JsonResponse{
        try{
            return $this->apiResponse('0000', $message, [
                'name' => Auth::guard()->user()->name,
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
                'photoRel' => [
                    'file' => Auth::guard()->user()->photoRel->file ?? '',
                    'name' => Auth::guard()->user()->photoRel->name ?? '',
                    'ext' => Auth::guard()->user()->photoRel->ext ?? '',
                    'path' => Auth::guard()->user()->photoRel->path ?? '',
                ],
                's_not' => Auth::guard()->user()->s_not,
                's_loc' => Auth::guard()->user()->s_loc,
                's_b_date' => Auth::guard()->user()->s_b_date,
            ]);
        }catch (\Exception $e){
            return $this->apiResponse($code, __('Error while processing your request. Please contact an administrator'));
        }
    }

    public function getSpecificUserData($user): array|false {
        try{
            return [
                'name' => $user->name,
                'username' => $user->username,
                'birth_date' => $user->birth_date,
                'birth_date_f' => Carbon::parse($user->birth_date)->format('d.m.Y'),
                'city' => $user->city,
                'teams' => [
                    'status' => isset($user->teamsRel),
                    'team' => $user->teamsRel->team ?? null,
                    'national_team' => $user->teamsRel->national_team ?? null
                ],
                'photoRel' => [
                    'file' => $user->photoRel->file ?? '',
                    'name' => $user->photoRel->name ?? '',
                    'ext'  => $user->photoRel->ext ?? '',
                    'path' => $user->photoRel->path ?? '',
                ]
            ];
        }catch (\Exception $e){
            return false;
        }
    }
}
