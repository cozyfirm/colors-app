<?php

namespace App\Http\Controllers\API\Posts;

use App\Http\Controllers\Controller;
use App\Models\Core\MyFile;
use App\Models\Posts\Post;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller{
    use FileTrait, ResponseTrait;
    protected string $_file_path = 'files/posts';

    public function save(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            /**
             *  Save file to __files table; return object | null in case of failure
             */
            $request['path'] = $this->_file_path;
            $file = $this->saveFile($request, 'file');

            /** @var PostContent $request->content */
            $post = Post::create([
                'user_id' => $user->id,
                'content' => $request->content,
                'file_id' => $file?->id,
                'public' => $request->public
            ]);

            $post->file_rel = $file;

            return $this->apiResponse('0000', __('Teams saved!'), [
                'post' => $post
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('2001', __('Error while processing your request. Please contact an administrator'));
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
