<?php

namespace App\Http\Controllers\API\MatchChat;

use App\Http\Controllers\Controller;
use App\Models\Social\Groups\Group;
use App\Models\SystemCore\SeasonMatch;
use App\Models\SystemCore\Users\UserTeams;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchChatController extends Controller{
    use ResponseTrait, LogTrait;

    public function fetch(Request $request): JsonResponse{
        try{
            $teams = UserTeams::where('user_id', '=', $request->user_id)->first();
            if(!$teams) return $this->apiResponse('4201', __('User did not select any clubs'));

            $matches = SeasonMatch::where(function ($query) use ($teams) {
                $query->where('home_team', '=', $teams->team)
                    ->orWhere('home_team', '=', $teams->national_team)
                    ->orWhere('visiting_team', '=', $teams->team)
                    ->orWhere('visiting_team', '=', $teams->national_team);
            })->where('date', '>=', date('Y-m-d'))
                ->with('seasonRel.leagueRel')
                ->with('homeRel')
                ->with('visitorRel')
                ->get(['season_id', 'home_team', 'visiting_team', 'date', 'options']);


            return $this->apiResponse('0000', __('Success'), [
                'matches' => $matches->toArray()
            ]);
        }catch (\Exception $e){
            $this->write('API: MatchChatController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('4200', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
