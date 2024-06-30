<?php

namespace App\Traits\Common;

use App\Models\Core\MyFile;
use Illuminate\Http\Request;

trait FileTrait{
    /**
     * @param Request $request
     * @param $key
     * @param string $type
     * @return MyFile|null
     *
     * Save file to storage
     */
    public function saveFile(Request $request, $key, string $type = 'file'): MyFile | null{
        if($request->has($key)){
            try{
                $file = $request->file($key);
                $ext = pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);
                $name = md5($file->getClientOriginalName().time()).'.'.$ext;

                $file->move($request->path, $name);

                if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') $type = 'img';

                /** @var PATH_TO_FILE $request->path */
                return MyFile::create([
                    'file' => $file->getClientOriginalName(),
                    'name' => $name,
                    'ext' => $ext,
                    'type' => $type,
                    'path' => $request->path
                ]);
            }catch (\Exception $e){ return null; }
        }else return null;
    }
    public function remove($id): void{
        try{
            $file = MyFile::where('id', '=', $id)->first();
            // unlink(public_path($file->getFile()));
            $file->delete();
        }catch (\Exception $e){}
    }
}
