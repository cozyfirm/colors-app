<?php

namespace App\Http\Controllers\API\OpenApi;

use App\Http\Controllers\Controller;
use App\Models\SystemCore\Club;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubsController extends Controller{
    use ResponseTrait;

    /* -------------------------------------------------------------------------------------------------------------- */
    /*
     *  Clubs API system
     */

    /**
     * Get club info by name; Probably AJAX Search
     *
     * @param Request $request
     * @return false|string
     */
    public function searchByName(Request $request): false|string {
        try{
            return json_encode(Club::where('name', 'like', '%' . ($request->term['term']) . '%')->with('venueRel')->get());
        }catch (\Exception $e){ return json_encode(['code' => '0001', 'message' => __('Error ..')]);  }
    }

    /**
     * Fetch Club by ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchByID(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000', 'Success: Get club by ID', [
                'club' => Club::where('id', '=', $request->id)->with('countryRel:id,name,name_ba,code,flag')->first(['id', 'name', 'flag', 'country_id', 'national', 'code', 'gender'])->toArray()
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('8010', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch Club by Name
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchByName(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000', 'Success: Get club by ID', [
                'club' => Club::where('name', 'LIKE', '%' . $request->name . '%')->with('countryRel:id,name,name_ba,code,flag')->get(['id', 'name', 'flag', 'country_id', 'national', 'code', 'gender'])->toArray()
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('8010', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
