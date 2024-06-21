<?php

namespace App\Traits\Common;

use App\Models\Core\File;
use Illuminate\Http\Request;

trait FileTrait{
    /**
     * @param Request $request
     * @param $key
     * @param string $type
     * @return void|null
     *
     * Save file to storage
     */
    public function saveFile(Request $request, $key, string $type = 'img'){
        if($request->has($key)){
            try{
                $file = $request->file($key);
                $ext = pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);
                $name = md5($file->getClientOriginalName().time()).'.'.$ext;

                $file->move($request->path, $name);

                return File::create([
                    'file' => $file->getClientOriginalName(),
                    'name' => $name,
                    'ext' => $ext,
                    'type' => $type,
                    'path' => $request->path
                ]);
            }catch (\Exception $e){ dd($e); return null; }
        }else return null;
    }
    public function remove($id){
        try{
            $file = File::where('id', $id)->first();
            // unlink(public_path($file->getFile()));
            $file->delete();
        }catch (\Exception $e){
            dd($e);
        }
    }
}
