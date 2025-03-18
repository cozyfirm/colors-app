<?php

namespace App\Http\Controllers\API\Posts;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\MyFile;
use App\Models\Posts\Post;
use App\Models\Posts\PostFile;
use App\Models\Posts\PostLike;
use App\Models\Social\Fans\Fan;
use App\Models\Social\Fans\FanRequest;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use App\Traits\Posts\PostTrait;
use App\Traits\Users\UserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller{
    use FileTrait, ResponseTrait, LogTrait, UserTrait, PostTrait;
    protected string $_file_path = 'files/posts';
    protected int $_number_of_posts = 10;

    /**
     * Create new post, using:
     *      - user_id
     *      - description
     *      - files
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse{
        try{
            /* Extract files from request */
            $files = $request->file('files');
            if(!isset($files)) return $this->apiResponse('3101', __('No files selected'));

            /* Make sure there is no exceptions */
            if($request->public != 0 and $request->public != 1) $request['public'] = 0;

            /**
             *  Save post and return saved file
             */
            return $this->savePost($request, $request->public);
        }catch (\Exception $e){
            $this->write('API: PostsController::save()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3100', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Delete:
     *      1. Files (__files)
     *      2. Relationships (posts__files)
     *      3. Post (posts)
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse{
        try{
            $post = Post::where('user_id', '=', Auth::user()->id)->where('id', '=', $request->post_id)->first();
            if(!$post){
                return $this->apiResponse('3105', __('You have no privilege to delete this post!'));
            }else{
                return $this->deletePost($post);
            }
        }catch (\Exception $e){
            $this->write('API: PostsController::delete()', $e->getCode(), $e->getMessage(), $request);
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
     * Fetch posts by particular user
     *
     * @param Request $request
     * @param $user
     * @param null $public
     * @return JsonResponse
     */
    public function fetchPostsByUser(Request $request, $user, $public = null): mixed{
        try{
            if(isset($request->number)) $this->_number_of_posts = $request->number;
            // $posts = Group::with('fileRel:id,file,name,ext,path')->select(['id', 'file_id', 'name', 'public', 'description', 'reactions', 'members']);
            $posts = Post::where('user_id', '=', $user->id);

            /* When fetching only public posts */
            if(isset($public) and $public){
                $posts = $posts->where('public', '=', $public);
            }

            $posts = $posts
                ->with('fileRel.fileRel:id,file,name,ext,path')
                ->with('fileRel:id,post_id,file_id')
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,username,photo')
                ->select(['id', 'user_id', 'description', 'public', 'views', 'likes', 'comments'])->orderBy('id', 'DESC');
            $posts = Filters::filter($posts, $this->_number_of_posts);

            return $posts->toArray();
        }catch (\Exception $e){
            $this->write('API: PostsController::fetchPostsByUser()', $e->getCode(), $e->getMessage(), $request);
            return false;
        }
    }

    /**
     * Fetch posts by some user
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchMyPosts(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            $posts = $this->fetchPostsByUser($request, $user);
            if($posts){
                return $this->apiResponse('0000', __('Success'), [
                    'posts' => $posts
                ]);
            }else{
                return $this->apiResponse('2021', __('Error while processing your request. Please contact an administrator'));
            }
        }catch (\Exception $e){
            $this->write('API: PostsController::fetchMyPosts()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2021', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch posts for specific user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchUserPosts(Request $request): JsonResponse{
        try{
            if(!isset($request->user_id)) return $this->apiResponse('3110', __('User ID not found'));
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();
            $otherUser = User::where('id', '=', $request->user_id)->first();

            $fanInfo = Fan::where('user_id', '=', $request->user_id)->where('fan_id', '=', $user->id)->first();
            $fanRequestStatus = FanRequest::where('from', '=', $user->id)->where('to', '=', $request->user_id)->first(['status']);

            $posts = $this->fetchPostsByUser($request, $otherUser, !isset($fanInfo));
            if($posts){
                return $this->apiResponse('0000', __('Success'), [
                    'posts' => $posts,
                    'fanInfo' => [
                        'amIFan' => isset($fanInfo),
                        'fanRequest' => $fanRequestStatus
                    ],
                    'userInfo' => $this->getSpecificUserData($otherUser)
                ]);
            }else{
                return $this->apiResponse('2021', __('Error while processing your request. Please contact an administrator'));
            }
        }catch (\Exception $e){
            $this->write('API: PostsController::fetchUserPosts()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2021', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Like or unlike post; If sample exists, dislike (remove posts__likes) otherwise create sample
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function like(Request $request): JsonResponse{
        try{
            if(!isset($request->post_id)) return $this->apiResponse('3115', __('Unknown post'));

            /** Like or unlike post and return statistics and action */
            return $this->likePost($request);
        }catch (\Exception $e){
            $this->write('API: PostsController::like()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2021', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
