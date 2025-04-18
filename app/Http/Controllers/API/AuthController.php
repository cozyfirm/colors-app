<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\API\Auth\GeneratePIN;
use App\Mail\API\Auth\WelcomeTo;
use App\Models\User;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use App\Traits\Users\UserTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller{
    use ResponseTrait, UserTrait, LogTrait;

    public function auth(Request $request): JsonResponse {
        try{
            /* ToDo - Check if email was verified */
            if(!isset($request->email))    return $this->apiResponse('1102', __('Please, enter your email'));
            if(!isset($request->password)) return $this->apiResponse('1103', __('Please, enter your password'));

            $user = User::where('email', '=', $request->email)->first();
            if(!$user) return $this->apiResponse('1104', __('Unknown email'));

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::user();

                /** Update FCM token when logged */
                User::where('email', '=', $request->email)->update(['fcm_token' => $request->fcm_token]);

                /** @var UserObject $user */
                return $this->apiResponse('0000', __('Success'), [
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'api_token' => $user->api_token,
                    'photo' => [
                        'hasPhoto' => (isset($user->photo)),
                        'img_path' => 'files/users/profile-photo',
                        'photo' => $user->photo ?? null
                    ],
                    'teams' => [
                        'status' => isset($user->teamsRel),
                        'team' => $user->teamsRel->team ?? null,
                        'national_team' => $user->teamsRel->national_team ?? null
                    ]
                ]);
            }else {
                return $this->apiResponse('1105', __('You have entered wrong password'));
            }
        }catch (\Exception $e){
            $this->write('API: AuthController::auth()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1101', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /* Register user routes */
    protected function passwordCheck(Request $request, $code = '1007'): array {
        try{
            if (strlen($request->password) < 8) throw new \Exception(__('Password must contain at least 8 characters'), $code);
            if (!preg_match("/\d/", $request->password)) throw new \Exception(__('Password must contain at least one digit'), $code);
            if (!preg_match("/[A-Z]/", $request->password) and !preg_match("/[a-z]/", $request->password)) throw new \Exception(__('Password must contain letters'), $code);
            if (!preg_match("/\W/", $request->password)) throw new \Exception(__('Password must contain at least one special character'), $code);

            return ["code" => "0000", "message" => "OK!"];
        }catch (\Exception $e){
            $this->write('API: AuthController::passwordCheck()', $e->getCode(), $e->getMessage(), $request);
            return ["code" => $e->getCode(), "message" => $e->getMessage()];
        }
    }

    /*
     *  Create new use account
     */
    public function register(Request $request): JsonResponse {
        try{
            /* Empty data check */
            if(!isset($request->email))    return $this->apiResponse('1002', __('Please, enter your email'));
            if(!isset($request->password)) return $this->apiResponse('1003', __('Please, enter your password'));
            if(!isset($request->username)) return $this->apiResponse('1004', __('Please, enter your username'));

            /* Check for email format */
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) return $this->apiResponse('1005', __('Email invalid'));

            /* Check for unique email and username */
            $user = User::where('email', $request->email)->first();
            if($user) return $this->apiResponse('1006', __('This email has already been used'));

            $user = User::where('username', $request->username)->first();
            if($user) return $this->apiResponse('1007', __('This username has already been used'));

            /* Password check */
            try{
                $passwordCheck = $this->passwordCheck($request, '1008');

                if($passwordCheck['code'] != '0000'){
                    return $this->apiResponse($passwordCheck['code'], $passwordCheck['message']);
                }
            }catch (\Exception $e){ return $this->apiResponse('1009', __('Error while processing your request. Please contact an administrator')); }

            /* Create new user */
            $request['password'] = Hash::make($request->password);
            $request->request->add(['api_token' => $this->generateHash($request->email) ]);
            // $request['email_verified_at'] = Carbon::now();

            $user = User::create($request->all());

            /* Send an email for new user */
            try{
                Mail::to($request->email)->send(new WelcomeTo($request->username, $request->email, $user->api_token));
            }catch (\Exception $e){}

            /* Return user and user data */
            return $this->apiResponse('0000', __('Your account has been created'), [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'api_token' => $user->api_token
            ]);
        }catch (\Exception $e){
            $this->write('API: AuthController::register()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /*
     *  Verify an email after registration:
     *      - post method
     *      - get method
     */
    public function verifyAnEmail(Request $request): JsonResponse{
        try{
            $user = User::where('username', $request->username)->where('api_token', $request->api_token)->first();

            if($user){
                $user->update(['email_verified_at' => Carbon::now()]);

                return $this->apiResponse('0000', __('Email verification successful'));
            }else return $this->apiResponse('1016', __('Email verification failed'));
        }catch (\Exception $e){
            $this->write('API: AuthController::verifyAnEmail()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1015', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function verifyAnEmailGET($username, $api_token): JsonResponse{
        try{
            $user = User::where('username', $username)->where('api_token', $api_token)->first();

            if($user){
                $user->update(['email_verified_at' => Carbon::now()]);

                return $this->apiResponse('0000', __('Email verification successful'));
            }else return $this->apiResponse('1018', __('Email verification falied'));
        }catch (\Exception $e){
            return $this->apiResponse('1017', __('Error while processing your request. Please contact an administrator'));
        }
    }
    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  When creating account, offer live check for:
     *      1. Email
     *      2. Username
     *      3. Password
     */
    public function checkEmail(Request $request): JsonResponse{
        try{
            if(!isset($request->email)) return $this->apiResponse('1021', __('Please, enter your email'));
            if(strlen($request->email) > 50) return $this->apiResponse('1022', __('Email too long'));

            /* Check for email format */
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) return $this->apiResponse('1023', __('Email invalid'));

            $user = User::where('email', $request->email)->first();
            if($user) return $this->apiResponse('1024', __('This email has already been used'));
            else return $this->apiResponse('0000', __('Email is available to use'));
        }catch (\Exception $e){
            $this->write('API: AuthController::checkEmail()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1020', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function checkUsername(Request $request): JsonResponse{
        try{
            if(!isset($request->username)) return $this->apiResponse('1025', __('Please, enter your username'));
            if(strlen($request->username) > 20) return $this->apiResponse('1026', __('Username too long'));

            $user = User::where('username', $request->username)->first();
            if($user) return $this->apiResponse('1027', __('This username has already been used'));
            else return $this->apiResponse('0000', __('Username is available to use'));
        }catch (\Exception $e){
            $this->write('API: AuthController::checkUsername()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1024', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function checkPassword(Request $request): JsonResponse{
        try{
            if(!isset($request->password)) return $this->apiResponse('1029', __('Please, enter your password'));

            try{
                $passwordCheck = $this->passwordCheck($request, '1031');

                if($passwordCheck['code'] != '0000'){
                    return $this->apiResponse($passwordCheck['code'], $passwordCheck['message']);
                }
            }catch (\Exception $e){ return $this->apiResponse('1030', __('Error while processing your request. Please contact an administrator')); }

            return $this->apiResponse('0000', __('Good password'));
        }catch (\Exception $e){
            $this->write('API: AuthController::checkPassword()', $e->getCode(), $e->getMessage());
            return $this->apiResponse('1028', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Restart password
     *      - Generate pin
     *      - Confirm PIN
     *      - Restart password
     */

    public function generatePIN(Request $request): JsonResponse {
        try{
            if(!isset($request->email)) return $this->apiResponse('1202', __('Please, enter your email'));

            $user = User::where('email', $request->email)->first();
            if(!$user) return $this->apiResponse('1203', __('Unknown email'));

            $pin = mt_rand(1000, 9999);
            $user->update(['restart_pin' => $pin]);

            Mail::to($request->email)->send(new GeneratePIN($user->username, $user->email, $pin));

            return $this->apiResponse('0000', __('Email sent successfully. Follow instructions'));
        }catch (\Exception $e){
            $this->write('API: AuthController::generatePIN()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1201', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function verifyPIN(Request $request): JsonResponse {
        try{
            if(!isset($request->email)) return $this->apiResponse('1204', __('Please, enter your email'));
            if(!isset($request->pin)) return $this->apiResponse('1205', __('Please, enter PIN code'));

            $user = User::where('email', $request->email)->where('restart_pin', $request->pin)->first();
            if(!$user) return $this->apiResponse('1206', __('Incorrect pin'));

            return $this->apiResponse('0000', __('Pin code is correct. Proceed to continue'));
        }catch (\Exception $e){
            $this->write('API: AuthController::verifyPIN()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1201', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function newPassword(Request $request): JsonResponse {
        try{
            if(!isset($request->email)) return $this->apiResponse('1207', __('Please, enter your email'));
            if(!isset($request->pin)) return $this->apiResponse('1208', __('Please, enter PIN code'));
            if(!isset($request->password)) return $this->apiResponse('1209', __('Please, enter your password'));

            /* Password check */
            try{
                $passwordCheck = $this->passwordCheck($request, $code = '1210');

                if($passwordCheck['code'] != '0000'){
                    return $this->apiResponse($passwordCheck['code'], $passwordCheck['message']);
                }
            }catch (\Exception $e){ return $this->apiResponse('1201', __('Error while processing your request. Please contact an administrator')); }

            $user = User::where('email', $request->email)->where('restart_pin', $request->pin)->first();
            if(!$user) return $this->apiResponse('1211', __('Incorrect pin'));

            $user->update(['password' =>  Hash::make($request->password), 'restart_pin' => null]);
            return $this->apiResponse('0000', __('Password changed successfully'), [
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'api_token' => $user->api_token,
            ]);
        }catch (\Exception $e){
            $this->write('API: AuthController::newPassword()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('1201', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
