<?php

namespace App\Http\Controllers\API\Fans;

use App\Http\Controllers\Controller;
use App\Traits\Common\CommonTrait;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller{
    use FileTrait, ResponseTrait, CommonTrait;

    public function search(Request $request): JsonResponse{
        try{
            if(empty($request->search)) return $this->apiResponse('2052', __('Empty user name'));

            return $this->apiResponse('0000', __('Success'),
                Group::where('name', 'LIKE', '%' . $request->name . '%')->with('fileRel:id,file,name,ext,path')->take(10)->get(['id', 'file_id', 'name', 'public', 'description', 'reactions', 'members'])->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('2051', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
