<?php

namespace App\Http\Controllers\API\OpenApi;

use App\Http\Controllers\Controller;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfoController extends Controller{
    use ResponseTrait;

    public function termsAndConditions(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000' ,'Success', [
                'content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry"
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('8110', __('Global error'));
        }
    }
    public function privacyPolicy(Request $request): JsonResponse{
        try{
            return $this->apiResponse('0000' ,'Success', [
                'content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry"
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('8120', __('Global error'));
        }
    }
}
