<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller{
    use ResponseTrait;

    public function getUserData(Request $request){
        try{
            return $this->jsonResponse('0000', "Logged user", [
                'username' => Auth::guard()->user()->username,
                'email' => Auth::guard()->user()->email
            ]);
        }catch (\Exception $e){
            return $this->jsonResponse('2001', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
