<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use App\Traits\Users\UserTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller{
    use ResponseTrait, UserTrait, FileTrait, LogTrait;

    protected string $_file_path = 'files/users/profile-photo';

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
            $user = User::where('username', '=', $request->username)->first();

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
            $this->write('API: UsersController::update()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2011', __('Error while processing your request. Please contact an administrator'));
        }
    }

    public function updateImage(Request $request): JsonResponse{
        try{
            if(!isset($request->photo)) return $this->apiResponse('2016', __('Please choose a photo'));

            $request['path'] = $this->_file_path;
            $file = $this->saveFile($request, 'photo');

            /* If user do not have image, skip it */
            if(!empty(Auth::guard()->user()->photo)) $this->removeFile(Auth::guard()->user()->photo);

            /* Update user photo ID */
            Auth::guard()->user()->update([
                'photo' => $file?->id,
            ]);

            return $this->getUserData($request);
        }catch (\Exception $e){
            $this->write('API: UsersController::updateImage()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2015', __('Error while processing your request. Please contact an administrator'));
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
            $this->write('API: UsersController::profileSettings()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2031', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /* ToDo - Select country */
}
