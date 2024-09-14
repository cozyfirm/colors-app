<?php

namespace App\Http\Controllers\Admin\SystemCore;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\Keyword;
use App\Models\SystemCore\Club;
use App\Models\SystemCore\League;
use App\Models\SystemCore\Season;
use App\Models\SystemCore\SeasonMatch;
use App\Models\SystemCore\SeasonTeam;
use App\Traits\Http\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SeasonsController extends Controller{
    use ResponseTrait;
    protected string $_path = 'admin.app.system-core.seasons.';

    public function index(): View{
        if(Auth()->user()->checkRole('sys-mod')){
            $seasons = Season::where('id', '>', 0);
        }else if(Auth()->user()->checkRole('league-mod')){
            /*
             *  Select only leagues that user has access to it
             */
            $leagueIDs = Auth::user()->getModeratorLeagues();
            $seasons = Season::whereIn('league_id', $leagueIDs);
        }

        $seasons = Filters::filter($seasons);
        $filters = [
            'season' => __('Year'),
            'leagueRel.name' => __('League')
        ];

        return view($this->_path.'index', [
            'seasons' => $seasons,
            'filters' => $filters
        ]);
    }
    public function getData($action, $id = null, $view = 'create'): View{
        if(Auth()->user()->checkRole('sys-mod')){
            $leagues = League::get();
        }else if(Auth()->user()->checkRole('league-mod')){
            /*
             *  Select only leagues that user has access to it
             */
            $leagueIDs = Auth::user()->getModeratorLeagues();
            $leagues = League::whereIn('id', $leagueIDs)->get();
        }


        $cLeagues = [];
        foreach ($leagues as $league) $cLeagues[$league->id] = $league->name . " (" . $league->countryRel->name_ba . ")";

        return view($this->_path. $view, [
            $action => true,
            'leagues' =>$cLeagues,
            // 'clubs' => Club::pluck('name', 'id'),
            'season' => isset($id) ? Season::where('id', $id)->first() : null
        ]);
    }
    public function create(): View{ return $this->getData('create'); }
    public function save(Request $request){
        try{
            $season = Season::create([
                'start_y' => $request->start_y,
                'end_y' => $request->end_y,
                'season' => $request->start_y . ' / ' . $request->end_y,
                'league_id' => $request->league_id
            ]);

            return $this->jsonSuccess(__('Informacije uspješno ažurirane'), route('admin.core.seasons.preview', ['id' => $season->id ]));
        }catch (\Exception $e){ return $this->jsonResponse( '17100', $e->getMessage()); }
    }
    public function preview($id): View{ return $this->getData('preview', $id); }
    public function edit($id): View{ return $this->getData('edit', $id); }
    public function update(Request $request){
        try{
            Season::where('id', $request->id)->update([
                'start_y' => $request->start_y,
                'end_y' => $request->end_y,
                'season' => $request->start_y . ' / ' . $request->end_y,
                'league_id' => $request->league_id
            ]);

            return $this->jsonSuccess(__('Informacije uspješno ažurirane'), route('admin.core.seasons.preview', ['id' => $request->id ]));
        }catch (\Exception $e){ return $this->jsonResponse( '17100', $e->getMessage()); }
    }
    public function lockSeason ($id): RedirectResponse{
        try{
            Season::where('id', $id)->update(['locked' => 1]);

            return back()->with('success', __('Uspješno! Sada možete vršiti unos utakmica!'));
        }catch (\Exception $e){ return back()->with('error', __('Desila se greška, molimo pokušajte ponovo!')); }
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Copy previous season and teams
     */
    public function copy($id): View{
        return $this->getData('copy', $id, 'copy');
    }
    public function copySeason(Request $request): JsonResponse{
        try{
            $season = Season::where('id', $request->season_id)->first();

            $newSeason = Season::create([
                'start_y' => $request->start_y,
                'end_y' => $request->end_y,
                'season' => $request->start_y . ' / ' . $request->end_y,
                'league_id' => $request->league_id
            ]);

            $teams = SeasonTeam::where('season_id', $season->id)->get();
            foreach ($teams as $team){
                SeasonTeam::create(['season_id' => $newSeason->id, 'team_id' => $team->team_id, 'created_by' => Auth::user()->id]);
            }

            return $this->jsonSuccess(__('Sezona uspješno kopirana'), route('admin.core.seasons.preview', ['id' => $newSeason->id ]));
        }catch (\Exception $e){ return $this->jsonError( '17100', $e->getMessage()) ;}
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Add clubs to seasons
     */
    public function saveTeam(Request $request): RedirectResponse{
        try{
            $added = SeasonTeam::where('season_id', $request->id)->where('team_id', $request->team_id)->first();
            if($added) return back()->with('error', __('Klub već dodan za ovu sezonu!'));
            SeasonTeam::create(['season_id' => $request->id, 'team_id' => $request->team_id, 'created_by' => Auth::user()->id]);

            return back()->with('success', __('Uspješno ste unijeli klub!'));
        }catch (\Exception $e){ return back()->with('error', __('Desila se greška, molimo pokušajte ponovo!')); }
    }

    public function deleteTeam(int $seasonID, int $teamID): RedirectResponse{
        try{
            $team = Club::where('id', $teamID)->first();
            SeasonTeam::where('season_id', $seasonID)->where('team_id', $teamID)->delete();

            return back()->with('success', 'Uspješno ste obrisali ' . ($team->name ?? '') . " iz ove sezone!" );
        }catch (\Exception $e){ return back()->with('error', __('Desila se greška, molimo pokušajte ponovo!')); }
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Add matches to season
     */
    public function matchSchedule ($season_id): View{
        $teams = SeasonTeam::where('season_id', $season_id)->get('team_id');
        $teams_arr = [];

        foreach ($teams as $team) $teams_arr[] = $team->team_id;

        return view($this->_path. 'match-schedule', [
            'season' => Season::where('id', $season_id)->first(),
            'teams' => Club::whereIn('id', $teams_arr)->pluck('name', 'id'),
            'options' => Keyword::where('type', 'league_option')->pluck('name', 'value'),
        ]);
    }
    public function saveMatchSchedule(Request $request): RedirectResponse{
        try{
            $request['date'] = Carbon::parse($request->date)->format('Y-m-d');
            if($request->home_team == $request->visiting_team) return back()->with('error', __('Odaberite različite timove!'));

            $exists = SeasonMatch::where('season_id', $request->season_id)
                ->where('home_team', $request->home_team)
                ->where('visiting_team', $request->visiting_team)
                ->where('date', $request->date)
                ->first();

            if($exists) return back()->with('error', __('Utakmica već unesena!'));

            SeasonMatch::create($request->all());
            return back()->with('success', __('Utakmica uspješno unesena!'));
        }catch (\Exception $e){ dd($e); return back()->with('error', __('Desila se greška, molimo da kontaktirate administratora!')); }
    }
    public function deleteMatchSchedule($id){
        try{
            SeasonMatch::where('id', $id)->delete();
            return back()->with('success', __('Uspješno obrisano!'));
        }catch (\Exception $e){}
    }
}
