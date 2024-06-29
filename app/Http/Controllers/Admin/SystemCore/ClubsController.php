<?php

namespace App\Http\Controllers\Admin\SystemCore;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\Countries;
use App\Models\Core\Keyword;
use App\Models\SystemCore\Club;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClubsController extends Controller{
    protected string $_path = 'admin.app.system-core.clubs.';
    protected string $_destination_path = "files/core/clubs";

    public function index(): View{
        $clubs = Club::where('id', '>', 0);
        $clubs = Filters::filter($clubs);
        $filters = [
            'name' => __('Title'),
            'code' => __('Short title'),
            'countryRel.name_ba' => __('Country'),
            'founded' => __('Founded'),
            'nationalRel.name' => __('National team')
        ];

        return view($this->_path.'index', [
            'clubs' => $clubs,
            'filters' => $filters
        ]);
    }
    public function getData($action, $id = null): View{
        return view($this->_path. 'create', [
            $action => true,
            'yesNo' => Keyword::where('type', 'da_ne')->pluck('name', 'value'),
            'countries' => Countries::where('used', 1)->pluck('name_ba', 'id'),
            'club' => isset($id) ? Club::where('id', $id)->first() : null
        ]);
    }
    public function create(): View{ return $this->getData('create'); }
    public function save(Request $request){
        try{
            if(!isset($request->flag)) return back()->with('error', __('Nije odabrana niti jedna datoteka!'));

            $file = $request->file('flag');
            $ext  = $file->getClientOriginalExtension();
            if($ext != 'png' and $ext != 'jpg' and $ext != 'jpeg' and $ext != 'svg') return back()->with('error', __('Format slike nije podržan !'));

            $fileName =  $request->code . "." . $ext;

            $file->move(public_path($this->_destination_path), $fileName);

            $club = Club::create([
                'name' => $request->name,
                'code' => $request->code,
                'country_id' => $request->country_id,
                'founded' => $request->founded,
                'national' => $request->national,
                'flag' => $fileName
            ]);

            return redirect()->route('system.core.clubs.preview', ['id' => $club->id]);
        }catch (\Exception $e){ return back()->with('error', $e->getMessage()); }
    }
    public function preview($id):View{ return $this->getData('preview', $id); }
    public function edit($id):View{ return $this->getData('edit', $id); }
    public function update(Request $request){
        try{
            Club::where('id', $request->id)->update([
                'name' => $request->name,
                'code' => $request->code,
                'country_id' => $request->country_id,
                'national' => $request->national,
                'founded' => $request->founded
            ]);

            if(isset($request->flag)){
                $file = $request->file('flag');
                $ext  = $file->getClientOriginalExtension();
                if($ext != 'png' and $ext != 'jpg' and $ext != 'jpeg' and $ext != 'svg') return back()->with('error', __('Format slike nije podržan !'));

                $fileName =  $request->code . "." . $ext;
                $file->move(public_path($this->_destination_path), $fileName);

                Club::where('id', $request->id)->update([
                    'flag' => $fileName
                ]);
            }

            return redirect()->route('admin.core.clubs.preview', ['id' => $request->id]);
        }catch (\Exception $e){ dd($e); return back()->with('error', $e->getMessage()); }
    }
}
