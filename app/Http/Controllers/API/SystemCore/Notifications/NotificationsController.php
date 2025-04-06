<?php

namespace App\Http\Controllers\API\SystemCore\Notifications;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\SystemCore\Notifications\Notification;
use App\Traits\Common\CommonTrait;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController extends Controller{
    use FileTrait, ResponseTrait, CommonTrait;
    protected int $_number_of_notifications = 10;

    public function myNotifications(Request $request): JsonResponse{
        try{
            if(isset($request->number)) $this->_number_of_notifications = $request->number;
            $notifications = Notification::where('user_id', '=', $request->user()->id)
                /* User notifications queries */
                ->with('fanRequestRel.userRel:id,username,photo')
                ->with('fanRequestRel:id,notification_id,from,content')
                /* Select only necessary data */
                ->select(['id', 'user_id', 'type', 'read_at']);

            $notifications = Filters::filter($notifications, $this->_number_of_notifications);

            return $this->apiResponse('0000', __('Success'),
                $notifications->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
