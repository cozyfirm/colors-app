<?php

namespace App\Http\Controllers\API\Social\Groups;

use App\Http\Controllers\Controller;
use App\Models\Social\Groups\Group;
use App\Models\Social\Groups\GroupMember;
use App\Models\User;
use App\Traits\Common\CommonTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupsMembershipController extends Controller{
    use ResponseTrait, CommonTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Fetch all members: Only for admins
     */
    public function allMembers(Request $request): JsonResponse{
        try{
            $group = Group::where('id', '=', $request->id)->first();
            if(!$group) return $this->apiResponse('3052', __('Unknown group'));
            if(!isset($group->adminsRel)) return $this->apiResponse('3053', __('Admin not found'));
            if(!$group->isAdmin($request->user_id)) return $this->apiResponse('3054', __('No permission found'));

            /* ToDo - Image and country */
            return $this->apiResponse('0000', __('Success'),
                GroupMember::where('group_id', '=', $group->id)->with('userRel:id,name,username,city,photo')->get(['id', 'user_id', 'status'])->toArray()
            );
        }catch (\Exception $e){
            return $this->apiResponse('3051', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Create new request for group membership
     */
    public function sendRequest(Request $request) : JsonResponse{
        try{
            $group = Group::where('id', '=', $request->id)->first();
            if(!$group) return $this->apiResponse('3055', __('Unknown group'));

            $membership = GroupMember::where('user_id', '=', $request->user_id)->where('group_id', $group->id)->first();
            if($membership) {
                if($membership->status == 'pending') return $this->apiResponse('3056', __('Request already sent'));
                else if($membership->status == 'accepted') return $this->apiResponse('3057', __('Already a member'));
                else if($membership->status == 'denied') return $this->apiResponse('3058', __('Request denied'));
                else return $this->apiResponse('3059', __('Unknown membership status'));
            }

            $status = 'pending';
            if($group->public != 0) $status = 'accepted';

            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $request->user_id,
                'role' => 'member',
                'status' => $status
            ]);

            return $this->apiResponse('0000', __('Request successfully sent'));
        }catch (\Exception $e){
            return $this->apiResponse('3051', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * Allow or deny group membership; Action performed only by admins (owners)
     */
    public function allowDenyRequest(Request $request) : JsonResponse{
        try{
            $membership = GroupMember::where('id', '=', $request->id)->first();
            if(!$membership) return $this->apiResponse('3059', __('Unknown membership'));

            $group = Group::where('id', '=', $membership->group_id)->first();
            if(!$group) return $this->apiResponse('3060', __('Unknown group'));
            if(!isset($group->adminsRel)) return $this->apiResponse('3061', __('Admin not found'));
            if(!$group->isAdmin($request->user_id)) return $this->apiResponse('3062', __('No permission found'));

            if(empty($request->status)) return $this->apiResponse('3063', __('Empty status'));
            else{
                if($request->status == 'accept'){
                    $membership->update(['status' => 'accepted']);
                }else if($request->status == 'deny'){
                    $membership->update(['status' => 'denied']);
                }else{
                    return $this->apiResponse('3064', __('Unknown action'));
                }

                $group->update(['members' => GroupMember::where('group_id', '=', $group->id)->where('status', 'accepted')->count()]);
            }

            return $this->apiResponse('0000', __('Success'));
        }catch (\Exception $e){
            return $this->apiResponse('3051', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * ToDo:: Remove later; Deprecated
     *
     * @param Request $request
     * @return JsonResponse
     *
     * Join to public group
     */
    public function join(Request $request) : JsonResponse{
        try{
            $group = Group::where('id', '=', $request->id)->with('fileRel:id,file,name,ext,path')->first(['id', 'file_id', 'name', 'public', 'description', 'reactions', 'members']);
            if(!$group) return $this->apiResponse('3065', __('Unknown group'));
            if($group->public == 0) return $this->apiResponse('3066', __('You cant join private groups'));

            $membership = GroupMember::where('user_id', '=', $request->user_id)->where('group_id', $group->id)->first();
            if($membership) {
                if($membership->status == 'accepted') return $this->apiResponse('3067', __('Already a member'));
            }

            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $request->user_id,
                'role' => 'member',
                'status' => 'accepted'
            ]);

            return $this->apiResponse('0000', __('Successfully joined'), [
                'group' => $group
            ]);
        }catch (\Exception $e){
            return $this->apiResponse('3051', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Leave group (Soft delete from membership table)
     * @param Request $request
     * @return JsonResponse
     */
    public function leave(Request $request) : JsonResponse{
        try{
            GroupMember::where('group_id', '=', $request->id)
                ->where('user_id', '=', $request->user_id)->delete();

            return $this->apiResponse('0000', __('Successfully deleted'));
        }catch (\Exception $e){
            return $this->apiResponse('3051', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
