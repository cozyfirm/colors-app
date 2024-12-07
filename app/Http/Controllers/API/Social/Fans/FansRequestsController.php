<?php

namespace App\Http\Controllers\API\Social\Fans;

use App\Http\Controllers\Controller;
use App\Models\Social\Fans\FanRequest;
use App\Traits\Common\CommonTrait;
use App\Traits\Common\FileTrait;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FansRequestsController extends Controller{
    use FileTrait, ResponseTrait, CommonTrait, LogTrait;

    /**
     * Create fan | friend request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse{
        try{
            if(!isset($request->to)){
                return $this->apiResponse('2062', __('Invalid request'));
            }else{
                if($request->to == Auth::guard()->user()->id) return $this->apiResponse('2063', __('Cannot add yourself'));

                $fanRequest = FanRequest::where('from', '=', Auth::guard()->user()->id)->where('to', '=', $request->to)->first();
                if(!$fanRequest){
                    FanRequest::create([
                        'from' => Auth::guard()->user()->id,
                        'to' => $request->to
                    ]);

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
     * Fetch requests: Query function
     *
     * @param Request $request
     * @param $type
     * @return array
     */
    public function fetchData(Request $request, $type): array{
        try{
            if($type == 'my-requests'){
                return FanRequest::where('to', '=', Auth::guard()->user()->id)
                    ->where('status', '=', 'pending')
                    ->with('fromRel.photoRel:id,file,name,ext,path')
                    ->with('fromRel.teamsRel.teamRel:id,name')
                    ->with('fromRel.teamsRel.nationalTeamRel:id,name')
                    ->with('fromRel.teamsRel:id,user_id,team,national_team')
                    ->with('fromRel:id,name,username,photo')
                    ->get(['from', 'to', 'status'])
                    ->toArray();
            }else{
                return FanRequest::where('from', '=', Auth::guard()->user()->id)
                    ->where('status', '=', 'pending')
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
            return $this->apiResponse('0000', __('Success'), $this->fetchData($request, "my-requests"));
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
            return $this->apiResponse('0000', __('Success'), $this->fetchData($request, "sent"));
        }catch (\Exception $e){
            $this->write('API: FansRequestsController::fetchSentRequests()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2061', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
