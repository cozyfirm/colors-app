<?php

namespace App\Traits\Posts;

use App\Models\Posts\Post;
use App\Models\Social\Groups\GroupMember;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
     * Check if user has permission to specific group
     *
     * @param $group_id
     * @param $user_id
     * @return array
     */
    protected function userHasPermissionToGroup($group_id, $user_id): array{
        try{
            $membership = GroupMember::where('group_id', '=', $group_id)->where('user_id', '=', $user_id)->first();

            if(!$membership) return ['code' => '3082', 'message' => 'User is not a group member'];
            else{
                if($membership->role != 'admin'){
                    if($membership->status != 'accepted') return ['code' => '3083', 'message' => 'Permission not granted'];
                }
            }

            return ['code' => '0000', 'message' => 'Permission granted'];
        }catch (\Exception $e){
            $this->write('API: GroupsPostsController::userHasPermissionToGroup()', $e->getCode(), $e->getMessage());
            return ['code' => '3100', 'message' => 'Error while processing your request. Please contact an administrator'];
        }
    }
}
