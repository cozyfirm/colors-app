<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\Countries;
use App\Models\User;
use App\Traits\Users\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsersController extends Controller{
    use UserTrait;
    /*
     *  My profile - User only available profile
     */
    protected string $_path = 'admin.app.users.';

    public function myProfile(): View{
        return view($this->_path. 'my-profile', [
            'codes' => Countries::pluck('phone_code', 'id'),
            'countries' => Countries::pluck('name_ba', 'id'),
            'user' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request){
        try{
            if($this->checkForAnEmail($request->email, Auth::user()->id)) return $this->jsonResponse('0101', __('Odabrani email se već koristi'));
            if(!$this->phoneLengthOK($request->phone)) return $this->jsonResponse('0102', __('Uneseni broj telefona nije validan'));

            /* Update user */
            User::where('id', Auth::user()->id)->update($request->except(['_token']));

            return $this->jsonSuccess(__('Informacije uspješno ažurirane'), route('system.users.my-profile'));
        }catch (\Exception $e){ dd($e); return $this->jsonResponse('0100', __('Desila se greška, molimo kontaktirajte administratora!')); }
    }


    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Admin role => Preview and edit all users
     */
    public function index (){
        $users = User::where('active', 1);
        $users = Filters::filter($users);
        $filters = [
            'name' => __('Name and surname'),
            'email' => __('Email'),
            'phone' => __('Phone'),
            'city' => __('City'),
            'countryRel.name_ba' => 'Country'
        ];

        return view($this->_path.'index', [
            'users' => $users,
            'filters' => $filters
        ]);
    }

    public function getData($action, $username = null){
        return view($this->_path.'preview', [
            'codes' => Countries::pluck('phone_code', 'id'),
            'countries' => Countries::pluck('name_ba', 'id'),
            $action => true,
            'user' => isset($username) ? User::where('username', $username)->first() : NULL
        ]);
    }
    public function preview($username){ return $this->getData('preview', $username); }
    public function edit($username){ return $this->getData('edit', $username); }
    public function update(Request $request){
        try{
            if($this->checkForAnEmail($request->email, $request->id)) return $this->jsonResponse('0101', __('Odabrani email se već koristi'));
            if(!$this->phoneLengthOK($request->phone)) return $this->jsonResponse('0102', __('Uneseni broj telefona nije validan'));

            /* Update user */
            User::where('id', $request->id)->update($request->except(['_token', 'id', '_method']));

            /* Get user object */
            $user = User::where('id', $request->id)->first();

            return $this->jsonSuccess(__('Informacije uspješno ažurirane'), route('system.users.preview', ['username' => $user->username ]));
        }catch (\Exception $e){ return $this->jsonResponse('0100', __('Desila se greška, molimo kontaktirajte administratora!')); }
    }
}
