<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\API\Auth\WelcomeTo;
use App\Models\User;
use App\Traits\Http\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller{
    use ResponseTrait;

    public function auth(Request $request): bool|string {
        try{
            if(!isset($request->username)) return $this->jsonResponse('1102', __('Please, enter your username'));
            if(!isset($request->password)) return $this->jsonResponse('1103', __('Please, enter your password'));

            $user = User::where('username', $request->username)->first();
            if(!$user) return $this->jsonResponse('1104', __('Unknown username'));

            if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
                $user = Auth::user();

                return $this->jsonResponse('0000', __('Success'), [
                    'username' => $user->username,
                    'email' => $user->email,
                    'api_token' => $user->api_token,
                ]);
            }else {
                return $this->jsonResponse('1105', __('You have entered wrong password'));
            }
        }catch (\Exception $e){
            return $this->jsonResponse('1101', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /* Register user routes */
    protected function passwordCheck(Request $request, $code = '10'): array {
        try{
            if (strlen($request->password) < 8) throw new \Exception(__('Password must contain at least 8 characters'), $code. '07');
            if (!preg_match("/\d/", $request->password)) throw new \Exception(__('Password must contain at least one digit'), $code. '07');
            if (!preg_match("/[A-Z]/", $request->password) and !preg_match("/[a-z]/", $request->password)) throw new \Exception(__('Password must contain letters'), '1007');
            if (!preg_match("/\W/", $request->password)) throw new \Exception(__('Password must contain at least one special character'), $code. '07');

            return ["code" => "0000", "message" => "OK!"];
        }catch (\Exception $e){
            return ["code" => $e->getCode(), "message" => $e->getMessage()];
        }
    }

    public function register(Request $request): bool|string {
        try{
            /* Empty data check */
            if(!isset($request->email))    return $this->jsonResponse('1002', __('Please, enter your email'));
            if(!isset($request->password)) return $this->jsonResponse('1003', __('Please, enter your password'));
            if(!isset($request->username)) return $this->jsonResponse('1004', __('Please, enter your username'));

            /* Check for unique email and username */
            $user = User::where('email', $request->email)->first();
            if($user) return $this->jsonResponse('1005', __('This email has already been used'));

            $user = User::where('username', $request->username)->first();
            if($user) return $this->jsonResponse('1006', __('This username has already been used'));

            /* Password check */
            try{
                $passwordCheck = $this->passwordCheck($request);

                if($passwordCheck['code'] != '0000'){
                    return $this->jsonResponse($passwordCheck['code'], $passwordCheck['message']);
                }
            }catch (\Exception $e){ return $this->jsonResponse('1008', __('Error while processing your request. Please contact an administrator')); }

            /* Create new user */
            $request['password'] = Hash::make($request->password);
            $request->request->add(['api_token' => hash('sha256', $request->email. '+'. time())]);
            $request['email_verified_at'] = Carbon::now();

            $user = User::create($request->all());

            /* Send an email for new user */
            Mail::to($request->email)->send(new WelcomeTo($request->username, $request->email));

            /* Return user and user data */
            return $this->jsonResponse('0000', __('Your account has been created'), [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'api_token' => $user->api_token
            ]);
        }catch (\Exception $e){
            return $this->jsonResponse('1001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Restart password
     *      - Generate pin
     *      - Confirm PIN
     *      - Restart password
     */

    public function generatePIN(Request $request): bool|string {
        try{
            if(!isset($request->email)) return $this->jsonResponse('1202', __('Please, enter your email'));

            $user = User::where('email', $request->email)->first();
            if(!$user) return $this->jsonResponse('1203', __('Unknown email'));

            $pin = mt_rand(1000, 9999);
            $user->update(['restart_pin' => $pin]);

            // ToDo -- Create email function
            // Mail::to($request->email)->send(new \App\Mail\Auth\GeneratePIN($request->username, $request->email, $pin));

            return $this->jsonResponse('0000', __('Email sent successfully. Follow instructions'));
        }catch (\Exception $e){
            return $this->jsonResponse('1201', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function verifyPIN(Request $request): bool|string {
        try{
            if(!isset($request->email)) return $this->jsonResponse('1204', __('Email cannot be empty'));
            if(!isset($request->pin)) return $this->jsonResponse('1205', __('PIN code cannot be empty'));

            $user = User::where('email', $request->email)->where('restart_pin', $request->pin)->first();
            if(!$user) return $this->jsonResponse('1206', __('Incorrect pin'));

            return $this->jsonResponse('0000', __('Pin code is correct. Proceed to continue'));
        }catch (\Exception $e){
            return $this->jsonResponse('1201', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function newPassword(Request $request): bool|string {
        try{
            if(!isset($request->email)) return $this->jsonResponse('1207', __('Email cannot be empty'));
            if(!isset($request->pin)) return $this->jsonResponse('1208', __('PIN code cannot be empty'));
            if(!isset($request->password)) return $this->jsonResponse('1209', __('Password cannot be empty'));

            /* Password check */
            try{
                $passwordCheck = $this->passwordCheck($request, $code = '12');

                if($passwordCheck['code'] != '0000'){
                    return $this->jsonResponse($passwordCheck['code'], $passwordCheck['message']);
                }
            }catch (\Exception $e){ return $this->jsonResponse('1201', __('Error while processing your request. Please contact an administrator')); }

            $user = User::where('email', $request->email)->where('restart_pin', $request->pin)->first();
            if(!$user) return $this->jsonResponse('1210', __('Incorrect pin'));

            $user->update(['password' =>  Hash::make($request->password), 'restart_pin' => null]);
            return $this->jsonResponse('0000', __('Password changed successfully'));
        }catch (\Exception $e){
            return $this->jsonResponse('1201', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
