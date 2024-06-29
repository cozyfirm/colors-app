<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SystemCore\Club;
use App\Models\User;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller{
    use ResponseTrait;

    public function nationalTeams(Request $request): JsonResponse {
        try{
            $teams = Club::whereHas('countryRel', function ($q) use ($request){
                $q->where('used', 1);
            })->where('national', 1)->where('name', 'LIKE', '%'.$request->team.'%')->with('countryRel:id,name,name_ba,flag')->get(['id', 'name', 'flag', 'country_id', 'gender'])->toArray();

            return $this->apiResponse('0000', __('Success'), $teams);
        }catch (\Exception $e){
            return $this->apiResponse('1301', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
