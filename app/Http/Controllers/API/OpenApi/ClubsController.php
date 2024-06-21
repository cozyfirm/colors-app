<?php

namespace App\Http\Controllers\API\OpenApi;

use App\Http\Controllers\Controller;
use App\Models\SystemCore\Club;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\Request;

class ClubsController extends Controller{
    use ResponseTrait;

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Clubs API system
     */

    public function searchByName(Request $request): false|string {
        try{
            return json_encode(Club::where('name', 'like', '%' . ($request->term['term']) . '%')->with('venueRel')->get());
        }catch (\Exception $e){ return json_encode(['code' => '0001', 'message' => __('Error ..')]);  }
    }
}
