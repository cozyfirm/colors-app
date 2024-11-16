<?php

namespace App\Http\Controllers\API\Social\Groups;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Core\MyFile;
use App\Models\Social\Groups\Group;
use App\Models\Social\Groups\GroupMember;
use App\Models\User;
use App\Traits\Common\CommonTrait;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller{
    use FileTrait, ResponseTrait, CommonTrait;

    protected string $_file_path = 'files/social/groups';
    protected int $_number_of_groups = 10;

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Create new group
     */
    public function save(Request $request): JsonResponse{
        try{
            if(!isset($request->photo)) return $this->apiResponse('3002', __('Please choose a photo'));
            if(strlen($request->name) > 99) return $this->apiResponse('3003', __('Group name too large! Maximum size is 100 chars!'));
            if(strlen($request->description) > 299) return $this->apiResponse('3004', __('Group description too large! Maximum size is 300 chars!'));

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            $request['path'] = $this->_file_path;
            $file = $this->saveFile($request, 'photo');

            /** @var PostContent $request->content */
            $group = Group::create([
                'hash' => $this->randomHash($user->id),
                'file_id' => $file?->id,
                'name' => $request->name,
                'public' => $request->public,
                'description' => $request->description
            ]);

            /* Add owner | admin to the group */
            $member = GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $user->id,
                'role' => 'admin',
                'note' => 'owner',
                'status' => 'accepted'
            ]);

            /* Add file info to string */
            $group->file_rel = $file;
            /* Add owner info to string */
            $group->owner_rel = $member;

            return $this->apiResponse('0000', __('Success'), [
                'group' => $group
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Update basic data of group
     */
    public function update(Request $request) : JsonResponse {
        try{
            if(strlen($request->name) > 99) return $this->apiResponse('3005', __('Group name too large! Maximum size is 100 chars!'));
            if(strlen($request->description) > 299) return $this->apiResponse('3006', __('Group description too large! Maximum size is 300 chars!'));

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            $group = Group::where('hash', $request->hash)->first();
            if(!$group) return $this->apiResponse('3007', __('Unknown group'));
            if(!isset($group->adminsRel)) return $this->apiResponse('3008', __('Admin not found'));
            if(!$group->isAdmin($user->id)) return $this->apiResponse('3009', __('No permission to edit group found'));

            /** @var PostContent $request->content */
            $group->update([
                'name' => $request->name,
                'public' => $request->public,
                'description' => $request->description
            ]);

            return $this->apiResponse('0000', __('Success'), [
                'group' => $group
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Update photo of group
     */
    public function updatePhoto(Request $request): JsonResponse{
        try{
            if(!isset($request->photo)) return $this->apiResponse('3010', __('Please choose a photo'));

            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            $group = Group::where('hash', $request->hash)->first();
            if(!$group) return $this->apiResponse('3011', __('Unknown group'));
            if(!isset($group->adminsRel)) return $this->apiResponse('3012', __('Admin not found'));
            if(!$group->isAdmin($user->id)) return $this->apiResponse('3013', __('No permission to edit group found'));

            $request['path'] = $this->_file_path;
            $file = $this->saveFile($request, 'photo');

            /* Remove old file */
            // MyFile::where('id', '=', $group->file_id)->delete();
            $this->removeFile($group->file_id);

            /** @var PostContent $request->content */
            $group->update([
                'file_id' => $file?->id,
            ]);

            $group->file_rel = $group->fileRel;

            return $this->apiResponse('0000', __('Success'), [
                'group' => $group
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /** ------------------------------------------------------------------------------------------------------------ **/
    /**
     *  Search groups by name
     */

    public function search(Request $request) : JsonResponse{
        try{
            if(empty($request->name)) return $this->apiResponse('3020', __('Empty group name'));

            return $this->apiResponse('0000', __('Success'),
                Group::where('name', 'LIKE', '%' . $request->name . '%')->with('fileRel:id,file,name,ext,path')->take(10)->get(['id', 'file_id', 'name', 'public', 'description', 'reactions', 'members'])->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /** ------------------------------------------------------------------------------------------------------------ **/
    /**
     *  Fetch groups as:
     *      1. All groups
     *      2. My groups
     *      3. Top groups
     */

    /**
     * Fetch All groups;
     * @param Request $request [api_token, number (default: 10), page]
     * @return JsonResponse
     */
    public function fetchAllGroups(Request $request): JsonResponse{
        try{
            if(isset($request->number)) $this->_number_of_groups = $request->number;

            $groups = Group::with('fileRel:id,file,name,ext,path')->select(['id', 'file_id', 'name', 'public', 'description', 'reactions', 'members']);;
            $groups = Filters::filter($groups, $this->_number_of_groups);

            return $this->apiResponse('0000', __('Success'),
                $groups->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch My Groups
     * @param Request $request [api_token, number (default: 10), page]
     * @return JsonResponse
     */
    public function fetchMyGroups(Request $request): JsonResponse{
        try{
            if(isset($request->number)) $this->_number_of_groups = $request->number;

            $groups = Group::whereHas('allMembersRel', function ($q){
                $q->where('user_id', Auth::user()->id);
            })->with('fileRel:id,file,name,ext,path')->select(['id', 'file_id', 'name', 'public', 'description', 'reactions', 'members']);;

            $groups = Filters::filter($groups, $this->_number_of_groups);

            return $this->apiResponse('0000', __('Success'),
                $groups->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch Top groups;
     * @param Request $request [api_token, number (default: 10), page]
     * @return JsonResponse
     */
    public function fetchTopGroups(Request $request): JsonResponse{
        try{
            if(isset($request->number)) $this->_number_of_groups = $request->number;

            $groups = Group::orderBy('members', 'DESC')->with('fileRel:id,file,name,ext,path')->select(['id', 'file_id', 'name', 'public', 'description', 'reactions', 'members']);
            $groups = Filters::filter($groups, $this->_number_of_groups);

            return $this->apiResponse('0000', __('Success'),
                $groups->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('3001', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
