<?php

namespace App\Http\Controllers\Admin\SystemCore;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\Countries;
use App\Models\Core\Keyword;
use App\Models\SystemCore\League;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeagueController extends Controller{
    protected string $_path = 'admin.app.system-core.league.';

    protected string $_destination_path = "files/core/leagues";

    public function index(){
        $leagues = League::where('id', '>', 0);
        $leagues = Filters::filter($leagues);
        $filters = [
            'name' => __('Title'),
            'typeRel.name' => __('Type'),
            'countryRel.name_ba' => __('Country'),
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
                'country_id' => $request->country_id
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
        }catch (\Exception $e){ dd($e); return back()->with('error', $e->getMessage()); }
    }
}
