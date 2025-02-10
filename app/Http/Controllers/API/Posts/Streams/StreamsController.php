<?php

namespace App\Http\Controllers\API\Posts\Streams;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Posts\Post;
use App\Models\Posts\PostLike;
use App\Models\User;
use App\Traits\Common\LogTrait;
use App\Traits\Common\TimeTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StreamsController extends Controller{
    use ResponseTrait, LogTrait, TimeTrait;
    protected int $_total_streams = 10;

    /**
     * Get home posts for specific user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse{
        try{
            if(isset($request->number)) $this->_total_streams = $request->number;

            $posts = Post::with('fileRel.fileRel:id,file,name,ext,path')
                ->with('fileRel:id,post_id,file_id')
                ->with('userRel:id,name,username')
                ->inRandomOrder()
                ->take($this->_total_streams)
                ->select(['id', 'user_id', 'description', 'views', 'likes', 'comments', 'created_at'])
                ->orderBy('id', 'desc');

            $posts = Filters::filter($posts, $this->_total_streams);

            return $this->apiResponse('0000', __('Success'),
                $posts->toArray()
            );
        }catch (\Exception $e){
            $this->write('API: StreamsController::index()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3200', __('Error while processing your request. Please contact an administrator'));
        }
    }

    public function getPostInfo(Request $request, $post): mixed{
        $post = $post->with('filesRel.fileRel:id,file,name,ext,path')
            ->with('filesRel:id,post_id,file_id')
            /** Photo Relationship */
            ->with('userRel.photoRel:id,file,name,ext,path')
            /** Team and national-team Relationship */
            ->with('userRel.teamsRel.teamRel:id,name,flag,code,gender')
            ->with('userRel.teamsRel.nationalTeamRel:id,name,flag,code,gender')
            ->with('userRel.teamsRel:id,user_id,team,national_team')
            ->with('userRel:id,name,username,photo')
            ->first(['id', 'user_id', 'description', 'views', 'likes', 'comments', 'created_at']);

        /** Check if post is liked */
        $post->liked = PostLike::where('post_id', '=', $post->id)
            ->where('user_id', '=', $request->user()->id)->count();

        return $post;
    }

    /**
     * Fetch post info:
     *      1. Basic post info
     *      2. User info
     *      3. Is post liked or not
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse{
        try{
            if(isset($request->post_id)){
                $post = Post::where('id', '=', $request->post_id);

                /* Fetch additional infos */
                $post = $this->getPostInfo($request, $post);

                /* Next and previous post */
                $previous = Post::where('id', '<', $request->post_id);
                $next     = Post::where('id', '>', $request->post_id);

                /** ToDo -- Add check for post permissions */

                return $this->apiResponse('0000', __('Success'), [
                    'previous' => $this->getPostInfo($request, $previous)->toArray(),
                    'post' => $post->toArray(),
                    'next' => $this->getPostInfo($request, $next)->toArray()
                ]);
            }else{
                return $this->apiResponse('3201', __('404 - Not found'));
            }
        }catch (\Exception $e){
            $this->write('API: StreamsController::fetch()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3200', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /** ------------------------------------------------------------------------------------------------------------- */

    /**
     * Search posts by conditions
     *
     * @param Request $request
     * @return array
     */
    public function getSearchedPosts(Request $request): array{
        return Post::where('description', 'like', '%'.$request->get('query').'%')
            ->with('userRel.photoRel:id,file,name,ext,path')
            ->with('userRel:id,name,username,photo')
            // ->where('public', '=', 1)
            ->inRandomOrder()
            ->take(10)
            ->get(['id', 'user_id', 'description', 'views', 'likes', 'comments', 'created_at'])
            ->toArray();
    }

    /**
     * Search users by conditions
     *
     * @param Request $request
     * @return array
     */
    public function getSearchedUsers(Request $request): array{
        return User::with('photoRel:id,file,name,ext,path')
            ->with('teamsRel.teamRel')
            ->with('teamsRel.nationalTeamRel')
            ->with('teamsRel:id,user_id,team,national_team')
            ->inRandomOrder()
            ->get(['id', 'name', 'username', 'photo'])
            ->take(10)->
            toArray();
    }

    /**
     * Main Search API for Streams
     *      1. Streams
     *      2. Users
     *      3. Groups
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse{
        try{
            $user = $request->user();

            return $this->apiResponse('0000', __('Success'), [
                'posts' => $this->getSearchedPosts($request),
                'users' => $this->getSearchedUsers($request)
            ]);
        }catch (\Exception $e){
            $this->write('API: StreamsController::search()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('3200', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
