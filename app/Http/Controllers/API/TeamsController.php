<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SystemCore\Club;
use App\Models\SystemCore\League;
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
    public function fetchTeams(Request $request) : JsonResponse {
        try{
            /* Not so good */
            /* $teams = Club::where('national', '=', 0)->where('name', 'LIKE', '%'.$request->team.'%')
                ->has('lastSeasonRel.seasonRel.leagueRel')
                ->with('lastSeasonRel.seasonRel.leagueRel:id,name,logo')
                ->get(['id', 'name', 'flag', 'gender'])->take(50)->toArray(); */

            /* Other logic */
            $teams = League::whereHas('seasonRel.teamRel.teamRel', function ($q) use ($request){
                $q->where('name', 'LIKE', '%'.$request->team.'%');
            })->has('seasonRel.teamRel.teamRel')
                ->with('seasonRel.teamRel.teamRel:id,name,flag,gender')
                ->get(['id', 'name', 'logo'])->take(50)->toArray();

            return $this->apiResponse('0000', __('Success'), $teams);
        }catch (\Exception $e){
            return $this->apiResponse('1311', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
