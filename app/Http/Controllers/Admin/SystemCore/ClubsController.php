<?php

namespace App\Http\Controllers\Admin\SystemCore;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\Countries;
use App\Models\Core\Keyword;
use App\Models\SystemCore\Club;
use App\Models\SystemCore\Venue;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClubsController extends Controller{
    use ResponseTrait;

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
            'nationalRel.name' => __('National team'),
            'genderRel.name' => __('Gender'),
            'activeRel.name' => __('Active')
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
            'gender' => Keyword::where('type', 'gender')->pluck('name', 'value'),
            'club' => isset($id) ? Club::where('id', '=', $id)->first() : null
        ]);
    }
    public function create(): View{ return $this->getData('create'); }
    public function save(Request $request): RedirectResponse{
        try{
            if(!isset($request->flag)) return back()->with('error', __('Nije odabrana niti jedna datoteka!'));

            $file = $request->file('flag');
            $ext  = $file->getClientOriginalExtension();
            if($ext != 'png' and $ext != 'jpg' and $ext != 'jpeg' and $ext != 'svg') return back()->with('error', __('Format slike nije podržan !'));

            // $fileName =  $request->code . "." . $ext;
            $fileName = md5($file->getClientOriginalName().time()).'.'.$ext;

            $file->move(public_path($this->_destination_path), $fileName);

            $club = Club::create([
                'name' => $request->name,
                'code' => $request->code,
                'country_id' => $request->country_id,
                'founded' => $request->founded,
                'national' => $request->national,
                'flag' => $fileName,
                'gender' => $request->gender
            ]);

            return redirect()->route('admin.core.clubs.preview', ['id' => $club->id]);
        }catch (\Exception $e){ return back()->with('error', $e->getMessage()); }
    }
    public function preview($id):View{ return $this->getData('preview', $id); }
    public function edit($id):View{ return $this->getData('edit', $id); }
    public function update(Request $request): RedirectResponse{
        try{
            Club::where('id', '=', $request->id)->update([
                'name' => $request->name,
                'code' => $request->code,
                'country_id' => $request->country_id,
                'national' => $request->national,
                'founded' => $request->founded,
                'gender' => $request->gender
            ]);

            if(isset($request->flag)){
                $file = $request->file('flag');
                $ext  = $file->getClientOriginalExtension();
                if($ext != 'png' and $ext != 'jpg' and $ext != 'jpeg' and $ext != 'svg') return back()->with('error', __('Format slike nije podržan !'));

                // $fileName =  $request->code . "." . $ext;
                $fileName = md5($file->getClientOriginalName().time()).'.'.$ext;
                $file->move(public_path($this->_destination_path), $fileName);

                Club::where('id', '=', $request->id)->update([
                    'flag' => $fileName
                ]);
            }

            return redirect()->route('admin.core.clubs.preview', ['id' => $request->id]);
        }catch (\Exception $e){ return back()->with('error', $e->getMessage()); }
    }

    public function activeStatus(Request $request){
        try{
            $value = ($request->value === 'false') ? 0 : 1;

            Club::where('id', '=', $request->id)->update(['active' => $value]);
            return $this->jsonResponse('0000', __('Uspješno'), []);
        }catch (\Exception $e){
            return $this->jsonResponse('0000', __('Greška'), []);
        }
    }

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     * Venues
     */
    public function editVenue($club_id): View{
        $club = Club::where('id', '=', $club_id)->first();
        $venue = Venue::where('id', '=', $club->venue_id)->first();
        if(!$venue){
            $venue = Venue::create();
            $club->update(['venue_id' => $venue->id]);
        }

        return view($this->_path. 'edit-venue', [
            'club' => $club,
            'venue' => $venue
        ]);
    }
    public function updateVenue(Request $request): RedirectResponse{
        try{
            Venue::where('id', '=', $request->id)->update([
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'capacity' => $request->capacity
            ]);

            return redirect()->route('admin.core.clubs.preview', ['id' => $request->club_id]);
        }catch (\Exception $e){ return back()->with('error', __('There has been error while processing your request!!')); }
    }
}
