<?php

namespace App\Http\Controllers\API\Social\Fans;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Social\Fans\Fan;
use App\Models\User;
use App\Traits\Common\CommonTrait;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FansController extends Controller{
    use FileTrait, ResponseTrait, CommonTrait;
    protected int $_number_of_results = 10;

    /**
     * @param Request $request [api_token, number (default: 10), page]
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse{
        try{
            if(isset($request->number)) $this->_number_of_results = $request->number;

            $fans = Fan::where('user_id', '=', Auth::guard()->user()->id)
                ->with('fanRel.photoRel:id,file,name,ext,path')
                ->with('fanRel.teamsRel.teamRel:id,name')
                ->with('fanRel.teamsRel.nationalTeamRel:id,name')
                ->with('fanRel.teamsRel:id,user_id,team,national_team')
                ->with('fanRel:id,name,username,photo');

            /* Apply filter, number of results and pagination */
            $fans = Filters::filter($fans, $this->_number_of_results);

            return $this->apiResponse('0000', __('Success'),
                $fans->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('2081', __('Error while processing your request. Please contact an administrator'));
        }
    }
    /**
     * Search my fans by name (% LIKE % query)
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse{
        try{
            if(empty($request->search)) return $this->apiResponse('2082', __('Empty user name'));

            return $this->apiResponse('0000', __('Success'),
                Fan::where('user_id', '=', Auth::guard()->user()->id)
                    ->whereHas('fanRel', function ($q) use ($request){
                        $q->where('name', 'LIKE', '%' . $request->search . '%');
                    })
                    ->with('fanRel.photoRel:id,file,name,ext,path')
                    ->with('fanRel.teamsRel.teamRel:id,name')
                    ->with('fanRel.teamsRel.nationalTeamRel:id,name')
                    ->with('fanRel.teamsRel:id,user_id,team,national_team')
                    ->with('fanRel:id,name,username,photo')
                    ->take($this->_number_of_results)
                    ->get()
                    ->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('2081', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Delete fan from my fan list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse{
        try{
            Fan::where('user_id', '=', Auth::guard()->user()->id)
                ->where('fan_id', '=', $request->fan_id)
                ->delete();

            return $this->apiResponse('0000', __('Success'));
        }catch (\Exception $e){
            return $this->apiResponse('2081', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
