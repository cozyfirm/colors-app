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

    public function search(Request $request): JsonResponse{
        try{
            if(empty($request->search)) return $this->apiResponse('2052', __('Empty user name'));

            return $this->apiResponse('0000', __('Success'),
                User::where('name', 'LIKE', '%' . $request->search . '%')
                    ->with('photoRel:id,file,name,ext,path')
                    ->with('teamsRel.teamRel:id,name')
                    ->with('teamsRel.nationalTeamRel:id,name')
                    ->with('teamsRel:id,user_id,team,national_team')
                    ->orderBy('id', 'ASC')
                    ->take(10)
                    ->get(['id', 'name', 'username', 'photo'])
                    ->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('2051', __('Error while processing your request. Please contact an administrator'));
        }
    }
}