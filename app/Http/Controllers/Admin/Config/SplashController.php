<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Config\OtherData;
use App\Models\User;
use App\Traits\Common\FileTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SplashController extends Controller{
    use FileTrait;

    protected string $_file_path = 'files/config/splash';
    protected string $_path = 'admin.app.config.splash.';

    public function index(): View{
        $screens = OtherData::where('type', 'splash');
        $screens = Filters::filter($screens);
        $filters = [
            'title' => __('Title'),
            'file_id' => __('File'),
            'views' => __('Views')
        ];

        return view($this->_path . 'index', [
            'screens' => $screens,
            'filters' => $filters
        ]);
    }
    public function create(): View{
        return view($this->_path . 'create', [
            'create' => true
        ]);
    }
    public function save(Request $request): RedirectResponse{
        try{
            $request['path'] = $this->_file_path;
            $image = $this->saveFile($request, 'image');

            $screen = OtherData::create([
                'type' => 'splash',
                'title' => $request->title,
                'file_id' => $image->id
            ]);

            return redirect()->route('admin.config.splash.edit', ['id' => $screen->id]);
        }catch (\Exception $e){
            return back();
        }
    }
    public function edit($id): View{
        return view($this->_path . 'create', [
            'edit' => true,
            'screen' => OtherData::where('id', $id)->first()
        ]);
    }
    public function update(Request $request): RedirectResponse{
        try{
            if(isset($request->image)){
                $request['path'] = $this->_file_path;
                $image = $this->saveFile($request, 'image');

                OtherData::where('id', $request->id)->update(['title' => $request->title, ['file_id' => $image->id ]]);
            }else{
                OtherData::where('id', $request->id)->update(['title' => $request->title]);
            }

            return redirect()->route('admin.config.splash.edit', ['id' => $request->id]);
        }catch (\Exception $e){
            return back();
        }
    }
    public function delete($id): RedirectResponse{
        OtherData::where('id', $id)->delete();

        return redirect()->route('admin.config.splash');
    }
}
