<?php

namespace App\Traits\Posts;

use App\Http\Controllers\Admin\Core\Filters;
use App\Models\Posts\CommentLike;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait CommentTrait{
    use FileTrait, ResponseTrait;

    protected int $_number_of_comments = 6;
    protected int $_number_of_replies = 2;
    /**
     * Save comment on post or comment on comment
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveComment(Request $request): JsonResponse{
        try{
            $commentsOnComment = 0;

            PostComment::create([
                'user_id' => $request->user_id,
                'post_id' => $request->post_id,
                'comment' => $request->comment,
                'parent_id' => $request->parent_id ?? null
            ]);

            /**
             *  Check for number of comments on comment
             */
            if(isset($request->parent_id)){
                $parent = PostComment::where('id', '=', $request->parent_id)->first();

                $parent->update([
                    'comments' => PostComment::where('parent_id', '=', $request->parent_id)->count()
                ]);

                $commentsOnComment = $parent->comments ?? 0;
            }

            /* Get total number of comments on post */
            $totalComments = PostComment::where('post_id', '=', $request->post_id)->count();

            /* Update number of comments on post */
            Post::where('id', '=', $request->post_id)->update([
                'comments' => $totalComments
            ]);
            /* ToDo:: Broadcast over sockets */

            return $this->apiResponse('0000', __('Successfully saved'), [
                'totalComments' => $totalComments,
                'commentsOnComment' => $commentsOnComment
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3090', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch comments and first two comments on comment
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchComment(Request $request): JsonResponse{
        try{
            if(!isset($request->page)) return $this->apiResponse('3090', __('Unknown page'));

            /* Number of comments on post, per request */
            if(isset($request->number)) $this->_number_of_comments = $request->number;
            /* Number of replies on comment, initially */
            if(isset($request->replies)) $this->_number_of_replies = $request->replies;

            $comments = PostComment::where('post_id', '=', $request->post_id)
                ->whereNull('parent_id')
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,name,username,photo')
                ->select(['id', 'post_id', 'user_id', 'comment', 'parent_id', 'likes', 'comments'])
                ->orderBy('id', 'DESC');

            $comments = Filters::filter($comments, $this->_number_of_comments);

            foreach ($comments as $comment){
                $comment->replies = PostComment::where('post_id', '=', $request->post_id)
                    ->where('parent_id', '=', $comment->id)
                    ->with('userRel.photoRel:id,file,name,ext,path')
                    ->with('userRel:id,name,username,photo')
                    ->take($this->_number_of_replies)
                    ->orderBy('id', 'DESC')
                    ->get(['id', 'post_id', 'user_id', 'comment', 'parent_id', 'likes', 'comments', 'created_at']);
            }

            return $this->apiResponse('0000', __('Success'), [
                'comments' => $comments->toArray()
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3090', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch comments on comment
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchCommentsOnCommentTrait(Request $request): JsonResponse{
        try{
            if(!isset($request->page)) return $this->apiResponse('3090', __('Unknown page'));

            /* Number of comments on post, per request */
            if(isset($request->number)) $this->_number_of_comments = $request->number;

            $comments = PostComment::where('post_id', '=', $request->post_id)
                ->where('parent_id', '=', $request->comment_id)
                ->with('userRel.photoRel:id,file,name,ext,path')
                ->with('userRel:id,name,username,photo')
                ->select(['id', 'post_id', 'user_id', 'comment', 'parent_id', 'likes', 'created_at'])
                ->orderBy('id', 'DESC');

            $comments = Filters::filter($comments, $this->_number_of_comments);

            return $this->apiResponse('0000', __('Success'), [
                'comments' => $comments->toArray()
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3090', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Delete comment; Only owner can delete it! Maybe admins or so??
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteComment(Request $request): JsonResponse{
        try{
            if(!isset($request->comment_id)) return $this->apiResponse('3190', __('Comment not found'));
            if(!isset($request->post_id)) return $this->apiResponse('3190', __('Post not found'));

            $comment = PostComment::where('user_id', '=', $request->user_id)->where('id', '=', $request->comment_id)->first();
            if(!$comment) return $this->apiResponse('3190', __('Action not allowed'));

            if($comment->parent_id){
                /** Comment on comment */
                PostComment::where('id', '=', $comment->parent_id)->update(['comments' => (PostComment::where('parent_id', '=', $comment->parent_id)->count() - 1)]);
            }else{
                /** Main comment; Delete all replies */
                PostComment::where('parent_id', '=', $comment->id)->delete();
            }

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
            return $this->apiResponse('3090', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Like or unlike comment by user
     * @param Request $request
     * @return JsonResponse
     */
    public function likeComment(Request $request): JsonResponse{
        try{
            /* Comment ID that should be like / disliked */
            if(!isset($request->comment_id)) return $this->apiResponse('3190', __('Comment not found'));

            $liked = false;

            $like = CommentLike::where('comment_id', '=', $request->comment_id)->where('user_id', '=', $request->user_id)->first();
            if(!$like){
                /* Create new like sample */
                CommentLike::create([
                    'comment_id' => $request->comment_id,
                    'user_id' => $request->user_id
                ]);

                $liked = true;
            }else{
                /* Remove like sample */
                CommentLike::where('comment_id', '=', $request->comment_id)->where('user_id', '=', $request->user_id)->delete();
            }
            /* Get total likes of comment */
            $totalLikes = CommentLike::where('comment_id', '=', $request->comment_id)->count();

            /* Update number of likes of comment */
            PostComment::where('id', '=', $request->comment_id)->update([
                'likes' => $totalLikes
            ]);
            /* ToDo:: Broadcast over sockets */

            return $this->apiResponse('0000', __('Successfully liked'), [
                'liked' => $liked,
                'totalLikes' => $totalLikes
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3090', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
