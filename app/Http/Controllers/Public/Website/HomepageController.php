<?php

namespace App\Http\Controllers\Public\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageController extends Controller{
    protected string $_path = 'public.website.';

    public function homepage(): View{
        return view($this->_path . 'homepage');
    }
}
