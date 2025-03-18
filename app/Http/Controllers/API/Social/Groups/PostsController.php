<?php

namespace App\Http\Controllers\API\Social\Groups;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\MyFile;
use App\Models\Posts\Post;
use App\Models\Posts\PostFile;
use App\Models\Posts\PostLike;
use App\Models\Social\Groups\Group;
use App\Models\Social\Groups\GroupMember;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use App\Traits\Posts\CommentTrait;
use App\Traits\Posts\PostTrait;
use App\Traits\Social\GroupTrait;
use App\Traits\Users\UserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostsController extends Controller{
    use FileTrait, ResponseTrait, LogTrait, UserTrait, GroupTrait, PostTrait, CommentTrait;
    protected string $_file_path = 'files/posts';
    protected int $_number_of_posts = 10;
    /**
     * Save post by user to specific group
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse{
        try{
            if(!isset($request->group_id)) return $this->apiResponse('3071', __('Group not found'));
            if(!$this->userHasPermissionToGroup($request)) return $this->apiResponse('3072', __('Not allowed to post'));

            $group = Group::where('id', '=', $request->group_id)->first();

            /** Save post and return saved post */
            return $this->savePost($request, $group->public, $group->id);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::save() - Global error', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Update post (allowed only to owner)
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse{
        try{
            return $this->updatePost($request);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::delete()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Delete group post
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse{
        try{
            $post = Post::where('id', '=', $request->post_id)->where('user_id', '=', $request->user_id)->first();
            if(!$post){
                $membership = GroupMember::where('group_id', '=', $request->group_id)->where('user_id', '=', $request->user_id)->first();

                if(!$membership){
                    return $this->apiResponse('3073', __('Not allowed to delete this post'));
                }else{
                    if($membership->role == 'admin'){
                        return $this->deletePost(Post::where('id', '=', $request->post_id)->first());
                    }
                    return $this->apiResponse('3073', __('Not allowed to delete this post'));
                }
            }else{
                return $this->deletePost($post);
            }
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::delete()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch group posts in order
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse{
        try{
            if(!isset($request->group_id)) return $this->apiResponse('3074', __('Group not found'));
            if(!$this->userHasPermissionToGroup($request)) return $this->apiResponse('3075', __('Not allowed to post'));

            /**
             *  Fetch posts
             */
            if(isset($request->number)) $this->_number_of_posts = $request->number;

            $posts = Post::where('group_id', '=', $request->group_id)
                ->with('fileRel.fileRel:id,file,name,ext,path')
                ->with('fileRel:id,post_id,file_id')
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,username,photo')
                /** Two comments */
                ->with('popularCommentsRel.userRel.photoRel:id,file,name,ext,path')
                ->with('popularCommentsRel.userRel:id,name,username,photo')
                ->with('popularCommentsRel:post_id,user_id,comment,likes,comments')
                /** Select only main stuffs */
                ->select(['id', 'user_id', 'description', 'public', 'views', 'likes', 'comments', 'created_at'])->orderBy('id', 'DESC');
            $posts = Filters::filter($posts, $this->_number_of_posts);

            return $this->apiResponse('0000', __('Success'), [
                'posts' => $posts->toArray()
            ]);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::fetch()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }

    public function like(Request $request): JsonResponse{
        try{
            if(!isset($request->group_id)) return $this->apiResponse('3076', __('Group not found'));
            if(!$this->userHasPermissionToGroup($request)) return $this->apiResponse('3077', __('Not allowed to post'));

            /** Like or unlike post and return statistics and action */
            return $this->likePost($request);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::like()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
