<?php

namespace App\Http\Controllers\API\OpenApi;

use App\Http\Controllers\Controller;
use App\Models\Core\Countries;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller{
    use ResponseTrait;

    /**
     * Get all countries
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountries(Request $request): JsonResponse {
        try{
            return $this->apiResponse('0000', 'Success: Fetch all countries',
                Countries::where('used', 1)->get(['id', 'name', 'name_ba', 'code', 'phone_code', 'flag'])->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('8000', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Get country by ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountryByID(Request $request): JsonResponse {
        try{
            return $this->apiResponse('0000', 'Success: Get Country By ID', [
                Countries::where('id', $request->id)->first(['id', 'name', 'name_ba', 'code', 'phone_code', 'flag'])
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('8001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Get country By Code
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountryByCode(Request $request): JsonResponse {
        try{
            return $this->apiResponse('0000', 'Success: Get Country By Code', [
                Countries::where('code', $request->code)->first(['id', 'name', 'name_ba', 'code', 'phone_code', 'flag'])
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('8002', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
