<?php

namespace App\Traits\Common;
use App\Models\Core\DBLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait LogTrait{
    /**
     *  This trait is used to log all errors across the system
     */

    public function write($title, $code, $message, Request $request = null): void{
        try{
            if(isset($request)){
                DBLog::create([
                    'title' => $title,
                    'route' => $request->route()->getName() !== null ? $request->route()->getName() : 'unknown',
                    'code' => $code,
                    'message' => $message,
                    'data' => json_encode($request->all())
                ]);
            }else{
                DBLog::create([
                    'title' => $title,
                    'route' => $request->route()->getName() !== null ? $request->route()->getName() : 'unknown',
                    'code' => $code,
                    'message' => $message
                ]);
            }
        }catch (\Exception $e){
            dd($e);
            Log::alert("LogTrait::write(): " . $e->getMessage());
        }
    }
}
