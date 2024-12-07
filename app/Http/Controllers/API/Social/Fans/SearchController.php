<?php

namespace App\Http\Controllers\API\Social\Fans;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Common\CommonTrait;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller{
    use FileTrait, ResponseTrait, CommonTrait;
    protected int $_number_of_results = 10;

    /**
     * Search fans by name - Input param search
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse{
        try{
            if(empty($request->search)) return $this->apiResponse('2052', __('Empty user name'));

            return $this->apiResponse('0000', __('Success'),
                User::where('name', 'LIKE', '%' . $request->search . '%')
                    ->where('role', '=', 'user')
                    ->with('photoRel:id,file,name,ext,path')
                    ->with('teamsRel.teamRel:id,name')
                    ->with('teamsRel.nationalTeamRel:id,name')
                    ->with('teamsRel:id,user_id,team,national_team')
                    ->orderBy('id', 'ASC')
                    ->take($this->_number_of_results)
                    ->get(['id', 'name', 'username', 'photo'])
                    ->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('2051', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Recommended fans for specific user; ToDo:: Change this random order behavior
     * @param Request $request
     * @return JsonResponse
     */
    public function recommended(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000', __('Success'),
                User::inRandomOrder()
                    ->where('role', '=', 'user')
                    ->with('photoRel:id,file,name,ext,path')
                    ->with('teamsRel.teamRel:id,name')
                    ->with('teamsRel.nationalTeamRel:id,name')
                    ->with('teamsRel:id,user_id,team,national_team')
                    ->orderBy('id', 'ASC')
                    ->take($this->_number_of_results)
                    ->get(['id', 'name', 'username', 'photo'])
                    ->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('2051', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
