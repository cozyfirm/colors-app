<?php

namespace App\Http\Controllers\API\Posts;

use App\Http\Controllers\Controller;
use App\Models\Core\MyFile;
use App\Models\Posts\Post;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller{
    use FileTrait, ResponseTrait, LogTrait;
    protected string $_file_path = 'files/posts';

    public function save(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            /* Extract files from request */
            $files = $request->file('files');

            if(!count($files)) return $this->apiResponse('3101', __('No files selected'));

            /* Make sure there is no exceptions */
            if($request->public != 0 and $request->public != 1) $request['public'] = 0;
            /* Set files path and add to request */
            $request['path'] = $this->_file_path;

            try{
                /**
                 *  Create new post
                 */
                $post = Post::create([
                    'user_id' => $user->id,
                    'description' => $request->description,
                    'public' => $request->public
                ]);
            }catch (\Exception $e){
                $this->write('API: PostsController::save() - Create new post', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('3102', __('Error while creating post. Check later'));
            }

            /* Save file, create DB object (DB Relationship on posts__files table) */
            foreach ($files as $file){
                $this->savePostFile($request, $file, $post->id);
            }

            return $this->apiResponse('0000', __('Post saved'));
        }catch (\Exception $e){
            $this->write('API: PostsController::save()', $e->getCode(), $e->getMessage(), $request);

            return $this->apiResponse('3100', __('Error while processing your request. Please contact an administrator'));
        }
    }
    public function delete(Request $request): JsonResponse{
        try{
            $post = Post::where('user_id', '=', Auth::user()->id)->where('id', '=', $request->post_id)->first();
            if(!$post){
                return $this->apiResponse('2012', __('You have no privilege to delete this post!'));
            }else{
                /**
                 *  First, remove the file
                 */
                MyFile::where('id', '=', $post->file_id)->delete();
                /**
                 *  Delete post | ToDo - Delete all comments and likes later !?
                 */
                $post->delete();

                return $this->apiResponse('0000', __('Successfully deleted'));
            }
        }catch (\Exception $e){
            return $this->apiResponse('2011', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /** ------------------------------------------------------------------------------------------------------------ **/
    /**
     *  Fetch user posts:
     *      1. Fetch my posts (by api token)
     *      2. Fetch other users posts (check if posts can be fetched)
     */

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchMyPosts(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            return $this->apiResponse('0000', __('Success'), [
                'posts' => Post::where('user_id', '=', $user->id)->with('fileRel')->get()
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('2021', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
