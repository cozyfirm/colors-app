<?php

namespace App\Traits\Common;

use App\Models\Core\MyFile;
use App\Models\Posts\PostFile;
use Illuminate\Http\Request;

trait FileTrait{
    use LogTrait;

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

    /**
     * @param $id
     * @return void
     *
     * Remove file from database and remove it from storage
     */
    public function removeFile($id): void{
        try{
            $file = MyFile::where('id', '=', $id)->first();
            try{
                unlink(public_path($file->fullName()));
            }catch (\Exception $e){ }
            $file->delete();
        }catch (\Exception $e){}
    }

    /**
     *  Posts, including:
     *      1. Streams
     *      2. Groups
     */
    public function savePostFile(Request $request, $file, $postID): void{
        try{
            $ext = pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);
            $name = md5($file->getClientOriginalName().time()).'.'.$ext;

            $file->move($request->path, $name);

            if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') $type = 'img';

            $file =  MyFile::create([
                'file' => $file->getClientOriginalName(),
                'name' => $name,
                'ext' => $ext,
                'type' => $type,
                'path' => $request->path
            ]);

            PostFile::create([
                'post_id' => $postID,
                'file_id' => $file->id
            ]);
        }catch (\Exception $e){
            $this->write('API: FileTrait::savePostFile()', $e->getCode(), $e->getMessage(), $request);
        }
    }
}
