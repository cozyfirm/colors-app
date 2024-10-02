<?php

namespace App\Http\Controllers\Admin\SystemCore;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\Countries;
use App\Models\Core\Keyword;
use App\Models\SystemCore\League;
use App\Models\SystemCore\LeagueModerator;
use App\Models\User;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeagueController extends Controller{
    use ResponseTrait;

    protected string $_path = 'admin.app.system-core.league.';

    protected string $_destination_path = "files/core/leagues";

    public function index(): View{
        if(Auth()->user()->checkRole('sys-mod')){
            $leagues = League::where('id', '>', 0);
        }else if(Auth()->user()->checkRole('league-mod')){
            /*
             *  Select only leagues that user has access to it
             */
            $leagueIDs = Auth::user()->getModeratorLeagues();
            $leagues = League::whereIn('id', $leagueIDs);
        }
        $leagues = Filters::filter($leagues);
        $filters = [
            'name' => __('Title'),
            'typeRel.name' => __('Type'),
            'countryRel.name_ba' => __('Country'),
            'genderRel.name' => __('Gender')
        ];

        return view($this->_path.'index', [
            'leagues' => $leagues,
            'filters' => $filters
        ]);
    }
    public function getData($action, $id = null): View{
        return view($this->_path. 'create', [
            $action => true,
            'type' => Keyword::where('type', 'league_type')->pluck('name', 'value'),
            'gender' => Keyword::where('type', 'gender')->pluck('name', 'value'),
            'league' => isset($id) ? League::where('id', $id)->first() : null,
            'countries' => Countries::where('used', 1)->pluck('name_ba', 'id'),
        ]);
    }
    public function create(): View{ return $this->getData('create'); }
    public function save(Request $request){
        try{
            if(!isset($request->logo)) return back()->with('error', __('Nije odabrana niti jedna datoteka!'));

            $file = $request->file('logo');
            $ext  = $file->getClientOriginalExtension();
            if($ext != 'png' and $ext != 'jpg' and $ext != 'jpeg' and $ext != 'svg') return back()->with('error', __('Format slike nije podržan !'));
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $ext;
            $file->move(public_path($this->_destination_path), $fileName);

            $league = League::create([
                'name' => $request->name,
                'type' => $request->type,
                'logo' => $fileName,
                'country_id' => $request->country_id,
                'gender' => $request->gender
            ]);

            return redirect()->route('system.core.leagues.preview', ['id' => $league->id]);
        }catch (\Exception $e){ return back()->with('error', $e->getMessage()); }
    }
    public function preview($id): View{ return $this->getData('preview', $id); }
    public function edit($id): View{ return $this->getData('edit', $id); }
    public function update(Request $request){
        try{
            League::where('id', $request->id)->update([
                'name' => $request->name,
                'type' => $request->type,
                'country_id' => $request->country_id,
                'gender' => $request->gender
            ]);

            if(isset($request->logo)){
                $file = $request->file('logo');
                $ext  = $file->getClientOriginalExtension();
                if($ext != 'png' and $ext != 'jpg' and $ext != 'jpeg' and $ext != 'svg') return back()->with('error', __('Format slike nije podržan !'));
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $ext;
                $file->move(public_path($this->_destination_path), $fileName);

                League::where('id', $request->id)->update([
                    'logo' => $fileName
                ]);
            }

            return redirect()->route('admin.core.league.preview', ['id' => $request->id]);
        }catch (\Exception $e){ return back()->with('error', $e->getMessage()); }
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  League moderators
     */
    public function addModerator($id): View{
        return view($this->_path. 'add-moderator', [
            'create' => true,
            'league' => League::where('id', $id)->first(),
            'users' => User::where('role', 'league-mod')->pluck('name', 'id')
        ]);
    }
    public function saveModerator(Request $request): JsonResponse{
        try{
            $moderator = LeagueModerator::where('user_id', $request->user_id)->where('league_id', $request->league_id)->first();
            if(!$moderator){
                $moderator = LeagueModerator::create(['user_id' => $request->user_id, 'league_id' => $request->league_id]);
            }

            return $this->jsonSuccess(__('Informacije uspješno ažurirane'), route('admin.core.league.preview', ['id' => $request->league_id ]));
        }catch (\Exception $e){ }
    }
    public function removeModerator($league_id, $id): RedirectResponse{
        try{
            LeagueModerator::where('league_id', $league_id)->where('user_id', $id)->delete();
            return redirect()->route('admin.core.league.preview', ['id' => $league_id]);
        }catch (\Exception $e){}
    }
}
