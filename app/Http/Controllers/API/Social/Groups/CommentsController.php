<?php

namespace App\Http\Controllers\API\Social\Groups;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Posts\CommentLike;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Social\Groups\Group;
use App\Models\Social\Groups\GroupMember;
use App\Models\User;
use App\Traits\Common\LogTrait;
use App\Traits\Common\TimeTrait;
use App\Traits\Http\ResponseTrait;
use App\Traits\Posts\CommentTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentsController extends Controller{
    use ResponseTrait, LogTrait, TimeTrait, CommentTrait;

    /** ToDO :: Apply logic for allowing users to comment or not */
    protected function canAddComment(Request $request): bool {
        try{
            $membership = GroupMember::where('group_id', '=', $request->group_id)->where('user_id', '=', $request->user_id)->first();
            if(!$membership) return false;
            else{
                if($membership->status != 'accepted') return false;
            }
            return true;
        }catch (\Exception $e){ return false; }
    }

    /**
     * Add comment to post or add comment to comment (on posts)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse{
        try{
            if(!isset($request->group_id)) return $this->apiResponse('3091', __('Group not found'));
            if(!isset($request->post_id)) return $this->apiResponse('3092', __('Post not found'));
            if(!isset($request->comment)) return $this->apiResponse('3093', __('Comment cannot be empty'));

            $group = Group::where('id', '=', $request->group_id)->first();
            if(!$group->public){
                if(!$this->canAddComment($request)) return $this->apiResponse('3094', __('Not allowed to comment'));
            }

            /** Save comment or comment on comment */
            return $this->saveComment($request);
        }catch (\Exception $e){
            $this->write('API: GroupsCommentsController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3090', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch comments of post:
     *      1. Fetch (default 6) comments
     *      2. Fetch (default 2) comments on comment with additional data
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse{
        try{
            if(!isset($request->group_id)) return $this->apiResponse('3091', __('Group not found'));
            if(!isset($request->post_id)) return $this->apiResponse('3092', __('Post not found'));

            $group = Group::where('id', '=', $request->group_id)->first();
            if(!$group->public){
                if(!$this->canAddComment($request)) return $this->apiResponse('3094', __('Not allowed to comment'));
            }

            return $this->fetchComment($request);
        }catch (\Exception $e){
            $this->write('API: GroupsCommentsController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3190', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch comments on comments; Simple version of fetching comments on posts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchCommentsOnComment(Request $request): JsonResponse{
        try{
            if(!isset($request->group_id)) return $this->apiResponse('3091', __('Group not found'));
            if(!isset($request->post_id)) return $this->apiResponse('3092', __('Post not found'));
            if(!isset($request->comment_id)) return $this->apiResponse('3095', __('Comment not found'));

            $group = Group::where('id', '=', $request->group_id)->first();
            if(!$group->public){
                if(!$this->canAddComment($request)) return $this->apiResponse('3094', __('Not allowed to comment'));
            }

            return $this->fetchCommentsOnCommentTrait($request);
        }catch (\Exception $e){
            $this->write('API: GroupsCommentsController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3190', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Delete comment and all replies to it
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse{
        try{
            return $this->deleteComment($request);
        }catch (\Exception $e){
            $this->write('API: GroupsCommentsController::delete()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3190', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Like or unlike comment; If sample exists, dislike (remove posts__comments_likes) otherwise create sample
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function like(Request $request): JsonResponse{
        try{
            return $this->likeComment($request);
        }catch (\Exception $e){
            $this->write('API: GroupsCommentsController::like()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3190', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
