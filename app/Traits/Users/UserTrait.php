<?php

namespace App\Traits\Users;

use App\User;

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
}
