<?php

namespace App\Http\Controllers\API\Posts;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Posts\CommentLike;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\User;
use App\Traits\Common\LogTrait;
use App\Traits\Common\TimeTrait;
use App\Traits\Http\ResponseTrait;
use App\Traits\Posts\CommentTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\Comment;

class CommentsController extends Controller{
    use ResponseTrait, LogTrait, TimeTrait, CommentTrait;

    /** ToDO :: Apply logic for allowing users to comment or not */
    protected function canAddComment(){

    }

    /**
     * Add comment to post or add comment to comment (on posts)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse{
        try{
            /* ToDo:: Check does user has privilege to comment this post */
            // if($this->canAddComment()) return $this->apiResponse('3151', __('Not allowed to comment'));
            if(!isset($request->post_id)) return $this->apiResponse('3152', __('Post not found'));
            if(!isset($request->comment)) return $this->apiResponse('3153', __('Comment cannot be empty'));

            /** Save comment or comment on comment */
            return $this->saveComment($request);
        }catch (\Exception $e){
            $this->write('API: CommentsController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3150', __('Error while processing your request. Please contact an administrator'));
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
            /* ToDo:: Check does user has privilege to comment this post */
            // if($this->canAddComment()) return $this->apiResponse('3161', __('Not allowed to comment'));

            return $this->fetchComment($request);
        }catch (\Exception $e){
            $this->write('API: CommentsController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3150', __('Error while processing your request. Please contact an administrator'));
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
            /* ToDo:: Check does user has privilege to comment this post */
            // if($this->canAddComment()) return $this->apiResponse('3165', __('Not allowed to comment'));

            return $this->fetchCommentsOnCommentTrait($request);
        }catch (\Exception $e){
            $this->write('API: CommentsController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3150', __('Error while processing your request. Please contact an administrator'));
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
            $this->write('API: CommentsController::delete()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3150', __('Error while processing your request. Please contact an administrator'));
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
            $this->write('API: CommentsController::like()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3150', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
