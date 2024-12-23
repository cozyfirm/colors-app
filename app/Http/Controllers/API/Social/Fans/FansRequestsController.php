<?php

namespace App\Http\Controllers\API\Social\Fans;

use App\Http\Controllers\Controller;
use App\Models\Social\Fans\Fan;
use App\Models\Social\Fans\FanRequest;
use App\Models\SystemCore\Notifications\Notification;
use App\Models\SystemCore\Notifications\NotificationFanRequest;
use App\Traits\Common\CommonTrait;
use App\Traits\Common\FileTrait;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FansRequestsController extends Controller{
    use FileTrait, ResponseTrait, CommonTrait, LogTrait;

    protected int $_number_of_results = 10;

    /**
     * Create fan | friend request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse{
        try{
            if(!isset($request->to)){
                return $this->apiResponse('2062', __('Invalid http request'));
            }else{
                if($request->to == Auth::guard()->user()->id) return $this->apiResponse('2063', __('Cannot add yourself'));

                $fanRequest = FanRequest::where('from', '=', Auth::guard()->user()->id)->where('to', '=', $request->to)->first();
                if(!$fanRequest){
                    FanRequest::create([
                        'from' => Auth::guard()->user()->id,
                        'to' => $request->to
                    ]);

                    /**
                     *  Create notification
                     */
                    try{
                        /**
                         *  ToDo - Add to notifications__fan_requests status field, which would sync with status field
                         *  or FK to users__fans__requests
                         */

                        $notification = Notification::create([
                            'user_id' => $request->to,
                            'type' => 'fan_request'
                        ]);

                        NotificationFanRequest::create([
                            'notification_id' => $notification->id,
                            'from' => Auth::guard()->user()->id,
                            'content' => 'sent you a fan request'
                        ]);
                    }catch (\Exception $exception){}

                    return $this->apiResponse('0000', __('Request sent successfully'));
                }else{
                    if($fanRequest->status == 'pending') return $this->apiResponse('2064', __('Request already sent'));
                    if($fanRequest->status == 'accepted') return $this->apiResponse('2065', __('Request already accepted'));
                    if($fanRequest->status == 'denied') return $this->apiResponse('2066', __('Request denied. Resending is not available at this moment'));
                }
            }
        }catch (\Exception $e){
            $this->write('API: FansRequestsController::create()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2061', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Update request status
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse{
        try{
            if(!isset($request->from) or !isset($request->status)){
                return $this->apiResponse('2063', __('Invalid http request'));
            }else{
                $fanRequest = FanRequest::where('from', '=', $request->from)->where('to', '=', Auth::guard()->user()->id)->first();

                if(!$fanRequest){
                    return $this->apiResponse('2064', __('Invalid request. Cannot find fan request'));
                }else{
                    if($request->status == 'accept'){
                        /* Search does object exists */
                        $fanObject = Fan::where('user_id', '=', Auth::guard()->user()->id)->where('fan_id', '=', $request->from)->first();

                        if(!$fanObject){
                            Fan::create([
                                'user_id' => Auth::guard()->user()->id,
                                'fan_id' => $request->from
                            ]);
                        }
                    }else{
                        /* ToDo: Maybe remove it or do nothing ?? */
                    }

                    FanRequest::where('from', '=', $request->from)->where('to', '=', Auth::guard()->user()->id)->update(['status' => $request->status]);
                }

                return $this->apiResponse('0000', __('Status updated'));
            }
        }catch (\Exception $e){
            $this->write('API: FansRequestsController::create()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2061', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch requests: Query function
     *
     * @param Request $request
     * @param $type
     * @param $status
     * @return array
     */
    public function fetchData(Request $request, $type, $status): array{
        try{
            if(isset($request->number)) $this->_number_of_results = $request->number;

            if($type == 'my-requests'){
                return FanRequest::where('to', '=', Auth::guard()->user()->id)
                    ->where('status', '=', $status)
                    ->with('fromRel.photoRel:id,file,name,ext,path')
                    ->with('fromRel.teamsRel.teamRel:id,name')
                    ->with('fromRel.teamsRel.nationalTeamRel:id,name')
                    ->with('fromRel.teamsRel:id,user_id,team,national_team')
                    ->with('fromRel:id,name,username,photo')
                    ->get(['from', 'to', 'status'])
                    ->toArray();
            }else{
                return FanRequest::where('from', '=', Auth::guard()->user()->id)
                    ->where('status', '=', $status)
                    ->with('toRel.photoRel:id,file,name,ext,path')
                    ->with('toRel.teamsRel.teamRel:id,name')
                    ->with('toRel.teamsRel.nationalTeamRel:id,name')
                    ->with('toRel.teamsRel:id,user_id,team,national_team')
                    ->with('toRel:id,name,username,photo')
                    ->get(['to', 'status'])
                    ->toArray();
            }
        }catch (\Exception $e){
            $this->write('API: FansRequestsController::fetchRequest()', $e->getCode(), $e->getMessage(), $request);
            return [];
        }
    }

    /**
     * Fetch my request
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchRequests(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000', __('Success'), $this->fetchData($request, "my-requests", "pending"));
        }catch (\Exception $e){
            $this->write('API: FansRequestsController::fetchRequests()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2061', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch my sent requests
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchSentRequests(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000', __('Success'), $this->fetchData($request, "sent", "pending"));
        }catch (\Exception $e){
            $this->write('API: FansRequestsController::fetchSentRequests()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2061', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
