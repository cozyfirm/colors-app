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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\Comment;

class CommentsController extends Controller{
    use ResponseTrait, LogTrait, TimeTrait;
    protected int $_number_of_comments = 6;
    protected int $_number_of_replies = 2;

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
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            /* ToDo:: Check does user has privilege to comment this post */
            // if($this->canAddComment()) return $this->apiResponse('3151', __('Not allowed to comment'));
            if(!isset($request->post_id)) return $this->apiResponse('3152', __('Post not found'));
            if(!isset($request->comment)) return $this->apiResponse('3153', __('Comment cannot be empty'));

            PostComment::create([
                'user_id' => $user->id,
                'post_id' => $request->post_id,
                'comment' => $request->comment,
                'parent_id' => $request->parent_id ?? null
            ]);

            /**
             *  Check for number of comments on comment
             */
            if(isset($request->parent_id)){
                PostComment::where('id', '=', $request->parent_id)->update([
                    'comments' => PostComment::where('parent_id', '=', $request->parent_id)->count()
                ]);
            }

            /* Get total number of comments on post */
            $totalComments = PostComment::where('post_id', '=', $request->post_id)->count();

            /* Update number of comments on post */
            Post::where('id', '=', $request->post_id)->update([
                'comments' => $totalComments
            ]);
            /* ToDo:: Broadcast over sockets */

            return $this->apiResponse('0000', __('Successfully saved'), [
                'totalComments' => $totalComments
            ]);
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
            /* Number of comments on post, per request */
            if(isset($request->number)) $this->_number_of_comments = $request->number;
            /* Number of replies on comment, initially */
            if(isset($request->replies)) $this->_number_of_replies = $request->replies;

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            /* ToDo:: Check does user has privilege to comment this post */
            // if($this->canAddComment()) return $this->apiResponse('3161', __('Not allowed to comment'));

            $comments = PostComment::where('post_id', '=', $request->post_id)
                ->whereNull('parent_id')
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,name,username,photo')
                ->select(['id', 'post_id', 'user_id', 'comment', 'parent_id', 'likes', 'comments']);

            $comments = Filters::filter($comments, $this->_number_of_comments);

            foreach ($comments as $comment){
                /* ToDo:: Make this dynamically */
                $comment->time = $this->sampleTime($comment->created_at);

                $comment->replies = PostComment::where('post_id', '=', $request->post_id)
                    ->where('parent_id', '=', $comment->id)
                    ->with('userRel.photoRel:id,file,name,ext,path')
                    ->with('userRel:id,name,username,photo')
                    ->take($this->_number_of_replies)
                    ->get(['id', 'post_id', 'user_id', 'comment', 'parent_id', 'likes', 'comments']);
            }

            return $this->apiResponse('0000', __('Successfully saved'), [
                'comments' => $comments->toArray()
            ]);
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
            /* Number of comments on post, per request */
            if(isset($request->number)) $this->_number_of_comments = $request->number;

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            /* ToDo:: Check does user has privilege to comment this post */
            // if($this->canAddComment()) return $this->apiResponse('3165', __('Not allowed to comment'));

            $comments = PostComment::where('post_id', '=', $request->post_id)
                ->where('parent_id', '=', $request->comment_id)
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,name,username,photo')
                ->orderBy('id')
                ->select(['id', 'post_id', 'user_id', 'comment', 'parent_id']);

            $comments = Filters::filter($comments, $this->_number_of_comments);

            foreach ($comments as $comment){
                /* ToDo:: Make this dynamically */
                $comment->time = $this->sampleTime($comment->created_at);
            }

            return $this->apiResponse('0000', __('Successfully saved'), [
                'comments' => $comments->toArray()
            ]);
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
            /* Number of comments on post, per request */
            if(!isset($request->comment_id)) return $this->apiResponse('3171', __('Comment not found'));

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();
            $comment = PostComment::where('user_id', '=', $user->id)->where('id', '=', $request->comment_id)->first();
            if(!$comment) return $this->apiResponse('3172', __('Action not allowed'));

            /* First, let's delete all replies */
            PostComment::where('parent_id', '=', $comment->id)->delete();
            /* Delete comment */
            $comment->delete();

            /* Get total number of comments on post */
            $totalComments = PostComment::where('post_id', '=', $request->post_id)->count();

            /* Update number of comments on post */
            Post::where('id', '=', $request->post_id)->update([
                'comments' => $totalComments
            ]);
            /* ToDo:: Broadcast over sockets */

            return $this->apiResponse('0000', __('Successfully deleted'), [
                'totalComments' => $totalComments
            ]);
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
            /* Comment ID that should be like / disliked */
            if(!isset($request->comment_id)) return $this->apiResponse('3175', __('Comment not found'));

            $liked = false;

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', '=', $request->api_token)->first();

            $like = CommentLike::where('comment_id', '=', $request->comment_id)->where('user_id', '=', $user->id)->first();
            if(!$like){
                /* Create new like sample */
                CommentLike::create([
                    'comment_id' => $request->comment_id,
                    'user_id' => $user->id
                ]);

                $liked = true;
            }else{
                /* Remove like sample */
                CommentLike::where('comment_id', '=', $request->comment_id)->where('user_id', '=', $user->id)->delete();
            }
            /* Get total likes of comment */
            $totalLikes = CommentLike::where('comment_id', '=', $request->comment_id)->count();

            /* Update number of likes of comment */
            PostComment::where('id', '=', $request->comment_id)->update([
                'likes' => $totalLikes
            ]);
            /* ToDo:: Broadcast over sockets */

            return $this->apiResponse('0000', __('Successfully deleted'), [
                'liked' => $liked,
                'totalLikes' => $totalLikes
            ]);
        }catch (\Exception $e){
            $this->write('API: CommentsController::like()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3150', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
