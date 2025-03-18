<?php

namespace App\Traits\Posts;

use App\Models\Core\MyFile;
use App\Models\Posts\Post;
use App\Models\Posts\PostFile;
use App\Models\Posts\PostLike;
use App\Models\Social\Groups\Group;
use App\Models\Social\Groups\GroupMember;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait PostTrait{
    use FileTrait, ResponseTrait;
    /**
     * Get group post with all other information
     *
     * @param $postID
     * @return JsonResponse
     */
    public function getGroupPost($postID): JsonResponse{
        try{
            $post = Post::where('id', '=', $postID)
                ->with('filesRel.fileRel:id,file,name,ext,path')
                ->with('filesRel:id,post_id,file_id')
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,username,photo')
                ->select(['id', 'user_id', 'description', 'public', 'group_id', 'views', 'likes', 'comments'])->orderBy('id', 'DESC')
                ->first();

            return $this->apiResponse('0000', __('Success'), [
                'post' => $post
            ]);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::getGroupPost()', $e->getCode(), $e->getMessage(), $postID);
            return $this->apiResponse('3100', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Save group post, only after all checks are passed
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveGroupPost(Request $request): JsonResponse{
        try{
            $group = Group::where('id', '=', $request->group_id)->first();

            /* Extract files from request */
            $files = $request->file('files');

            /* Set files path and add to request */
            $request['path'] = $this->_file_path;

            try{
                /**
                 *  Create new post
                 */
                $post = Post::create([
                    'user_id' => $request->user_id,
                    'description' => $request->description,
                    'public' => $group->public,
                    'group_id' => $group->id
                ]);
            }catch (\Exception $e){
                $this->write('API: PostTrait::saveGroupPost() - Create new post', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('3070', __('Error while creating post. Check later'));
            }

            /**
             *  Additional condition in case there is no images available at this moment
             */
            if(isset($files)){
                /* Save file, create DB object (DB Relationship on posts__files table) */
                foreach ($files as $file){
                    $this->savePostFile($request, $file, $post->id);
                }
            }

            return $this->getGroupPost($post->id);
        }catch (\Exception $e){
            try{
                if(isset($post)) $post->delete();
            }catch (\Exception $e){
                $this->write('API: PostTrait::saveGroupPost() - Error while deleting post', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('3070', __('Error while creating post. Check later'));
            }

            $this->write('API: PostTrait::saveGroupPost() - Global error', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while creating post. Check later'));
        }
    }

    /**
     * Update post (only allowed to owner of post)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePost(Request $request): JsonResponse{
        try{
            $post = Post::where('id', '=', $request->post_id)->where('user_id', '=', $request->user_id)->first();
            if(!$post){
                return $this->apiResponse('3073', __('Not allowed to delete this post'));
            }else{
                $post->update(['description' => $request->description]);

                return $this->getGroupPost($post->id);
            }
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::updatePost()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Delete post by owner and/or group admin !?
     *
     * @param $post
     * @return JsonResponse
     */
    public function deletePost($post): JsonResponse{
        try{
            if(!isset($post)) return $this->apiResponse('3070', __('Post not found!!'));
            $postFiles = PostFile::where('post_id', '=', $post->id)->get();

            /**
             *  First, remove the files and relationships
             */
            foreach ($postFiles as $postFile){
                MyFile::where('id', '=', $postFile->file_id)->delete();
                /* ToDo:: Remove physical file */
                $postFile->delete();
            }

            /**
             *  Delete post | Comments and likes are deleted by database
             */
            $post->delete();

            return $this->apiResponse('0000', __('Successfully deleted'));
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::getGroupPost()', $e->getCode(), $e->getMessage());
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Like or unlike post
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function likePost(Request $request): JsonResponse{
        try{
            if(!isset($request->post_id)) return $this->apiResponse('3070', __('Unknown post'));
            $post = Post::where('id', '=', $request->post_id)->first();
            if(!$post) return $this->apiResponse('3070', __('Unknown post'));

            $liked = false;

            $like = PostLike::where('post_id', '=', $request->post_id)->where('user_id', '=', $request->user_id)->first();
            if(!$like){
                /* Create new like sample */
                PostLike::create([
                    'post_id' => $request->post_id,
                    'user_id' => $request->user_id
                ]);

                $liked = true;
            }else{
                /* Remove like sample */
                PostLike::where('post_id', '=', $request->post_id)->where('user_id', '=', $request->user_id)->delete();
            }
            /* Get total likes of comment */
            $totalLikes = PostLike::where('post_id', '=', $request->post_id)->count();

            /* Update number of likes of comment */
            Post::where('id', '=', $request->post_id)->update([
                'likes' => $totalLikes
            ]);
            /* ToDo:: Broadcast over sockets */

            return $this->apiResponse('0000', __('Success'), [
                'liked' => $liked,
                'totalLikes' => $totalLikes
            ]);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::likePost()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3070', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
