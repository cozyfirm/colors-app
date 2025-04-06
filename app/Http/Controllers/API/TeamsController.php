<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SystemCore\Club;
use App\Models\SystemCore\League;
use App\Models\SystemCore\Users\UserTeams;
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
            })->where('national', 1)->where('active', '=', 1)->where('name', 'LIKE', '%'.$request->team.'%')->with('countryRel:id,name,name_ba,flag')->get(['id', 'name', 'flag', 'country_id', 'gender'])->toArray();

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
                $q->where('name', 'LIKE', '%'.$request->team.'%')->where('active', '=', 1);
            })->has('seasonRel.teamRel.teamRel')
                ->with('seasonRel.teamRel.teamRel:id,name,flag,gender')
                ->get(['id', 'name', 'logo'])->take(50)->toArray();

            return $this->apiResponse('0000', __('Success'), $teams);
        }catch (\Exception $e){
            return $this->apiResponse('1311', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     *  This method is used to save selected teams; Once for all
     */
    public function saveTeams(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            /** @var TeamID $request->team */
            $team = Club::where('id', '=', $request->team)->first();
            if(!$team) $this->apiResponse('1322', __('Error: Team not found!'));

            /** @var NationalTeamID $request->national_team */
            $nationalTeam = Club::where('id', '=', $request->national_team)->first();
            if(!$nationalTeam) $this->apiResponse('1323', __('Error: National team not found!'));

            /** @var TeamID $request->team */
            /** @var NationalTeamID $request->national_team */
            UserTeams::create([
                'user_id' => $user->id,
                'team' => $request->team,
                'national_team' => $request->national_team
            ]);

            /** @var TeamID $request->team */
            /** @var NationalTeamID $request->national_team */
            return $this->apiResponse('0000', __('Teams saved!'), [
                'team' => Club::where('id', '=', $request->team)->first(),
                'national_team' => Club::where('id', '=', $request->national_team)->first()
            ]);
        }catch (\Exception $e){
            if($e->getCode() == 23000){
                /** @var TeamID $request->team */
                /** @var NationalTeamID $request->national_team */
                return $this->apiResponse('0000', __('Teams saved!'), [
                    'team' => Club::where('id', '=', $request->team)->first(['id', 'name', 'flag', 'gender']),
                    'national_team' => Club::where('id', '=', $request->national_team)->first(['id', 'name', 'flag', 'gender'])
                ]);
            }
            return $this->apiResponse('1321', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
