<?php

namespace App\Http\Controllers\API\OpenApi;

use App\Http\Controllers\Controller;
use App\Models\Config\OtherData;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SplashController extends Controller{
    use ResponseTrait;

    public function get(Request $request): JsonResponse{
        try{
            $screen = OtherData::where('type', 'splash')->inRandomOrder()->with('fileRel:id,name,ext,type,path')->first(['id', 'title', 'file_id', 'views']);

            /* Increase number of views */
            if($screen) $screen->update(['views' => ($screen->views + 1)]);

            return $this->apiResponse('0000' ,'Success', [
                'default' => !isset($screen),
                'screen' => $screen
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('8100', __('Global error'));
        }
    }
}
