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
     * Save post by user to specific group
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            /* Extract files from request */
            $files = $request->file('files');

            /* Get group */
            $group = Group::where('id', '=', $request->group_id)->first();
            if(!$group) return $this->apiResponse('3081', __('Unknown group'));

            /** Permissions to group */
            $hasPermission = $this->userHasPermissionToGroup($request->group_id, $user->id);
            try{
                if($hasPermission['code'] != '0000') return $this->apiResponse($hasPermission['code'], __($hasPermission['message']));
            }catch (\Exception $e){
                $this->write('API: GroupsPostsController::save() - User permissions', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('3080', __('Error while creating post. Check later'));
            }

            /**
             *  At this part, we would allow posts without images
             */
            // if(!count($files)) return $this->apiResponse('3101', __('No files selected'));

            /* Set files path and add to request */
            $request['path'] = $this->_file_path;

            try{
                /**
                 *  Create new post
                 */
                $post = Post::create([
                    'user_id' => $user->id,
                    'description' => $request->description,
                    'public' => $group->public,
                    'group_id' => $group->id
                ]);
            }catch (\Exception $e){
                $this->write('API: GroupsPostsController::save() - Create new post', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('3080', __('Error while creating post. Check later'));
            }

            /* Save file, create DB object (DB Relationship on posts__files table) */
            foreach ($files as $file){
                $this->savePostFile($request, $file, $post->id);
            }

            return $this->getGroupPost($post->id);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::save() - Global error', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3080', __('Error while processing your request. Please contact an administrator'));
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
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            /* Get group */
            $group = Group::where('id', '=', $request->group_id)->first();
            if(!$group) return $this->apiResponse('3081', __('Unknown group'));

            $hasPermission = $this->userHasPermissionToGroup($request->group_id, $user->id);
            try{
                if($hasPermission['code'] != '0000') return $this->apiResponse($hasPermission['code'], __($hasPermission['message']));
            }catch (\Exception $e){
                $this->write('API: GroupsPostsController::delete() - User permissions', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('3080', __('Error while creating post. Check later'));
            }

            $post = Post::where('id', '=', $request->post_id)->where('user_id', '=', $user->id)->where('group_id', '=', $request->group_id)->first();
            if(!$post){
                return $this->apiResponse('3085', __('Permission not granted to delete this post'));
            }else{
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
                 *  Delete post | ToDo - Delete all comments and likes later !?
                 */
                $post->delete();

                return $this->apiResponse('0000', __('Successfully deleted'));
            }
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::delete()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2011', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch group posts in order
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            $group = Group::where('id', '=', $request->group_id)->first();
            if(!$group) return $this->apiResponse('3081', __('Unknown group'));

            if($group->public == 0){
                $membership = GroupMember::where('group_id', '=', $request->group_id)->where('user_id', '=', $user->id)->first();
                if(!$membership) return $this->apiResponse('3084', __('Access to this group not granted'));
                else{
                    if($membership->status != 'accepted') return $this->apiResponse('3084', __('Access to this group not granted'));
                }
            }

            /**
             *  Fetch posts
             */
            if(isset($request->number)) $this->_number_of_posts = $request->number;

            $posts = Post::where('group_id', '=', $request->group_id)
                ->with('fileRel.fileRel:id,file,name,ext,path')
                ->with('fileRel:id,post_id,file_id')
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,username,photo')
                ->select(['id', 'user_id', 'description', 'public', 'views', 'likes', 'comments'])->orderBy('id', 'DESC');
            $posts = Filters::filter($posts, $this->_number_of_posts);

            return $this->apiResponse('0000', __('Success'), [
                'posts' => $posts->toArray()
            ]);
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::fetch()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2011', __('Error while processing your request. Please contact an administrator'));
        }
    }

    public function like(Request $request): JsonResponse{
        try{
            if(!isset($request->post_id)) return $this->apiResponse('3085', __('Unknown post'));
            $post = Post::where('id', '=', $request->post_id)->first();
            if(!$post) return $this->apiResponse('3085', __('Unknown post'));

            $liked = false;

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            $like = PostLike::where('post_id', '=', $request->post_id)->where('user_id', '=', $user->id)->first();
            if(!$like){
                /* Create new like sample */
                PostLike::create([
                    'post_id' => $request->post_id,
                    'user_id' => $user->id
                ]);

                $liked = true;
            }else{
                /* Remove like sample */
                PostLike::where('post_id', '=', $request->post_id)->where('user_id', '=', $user->id)->delete();
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
            $this->write('API: PostsController::like()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('2021', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
