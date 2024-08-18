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

    public function allMembers(Request $request): JsonResponse{
        try{
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            $group = Group::where('id', $request->id)->first();
            if(!$group) return $this->apiResponse('3052', __('Unknown group'));
            if(!isset($group->adminsRel)) return $this->apiResponse('3053', __('Admin not found'));
            if(!$group->isAdmin($user->id)) return $this->apiResponse('3054', __('No permission found'));

            /* ToDo - Image and country */
            return $this->apiResponse('0000', __('Success'),
                GroupMember::where('group_id', $group->id)->with('userRel:id,name,username,city')->get(['id', 'user_id', 'status'])->toArray()
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
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            $group = Group::where('id', $request->id)->first();
            if(!$group) return $this->apiResponse('3055', __('Unknown group'));

            $membership = GroupMember::where('user_id', $user->id)->where('group_id', $group->id)->first();
            if($membership) {
                if($membership->status == 'pending') return $this->apiResponse('3056', __('Request already sent'));
                else if($membership->status == 'accepted') return $this->apiResponse('3057', __('Already a member'));
                else if($membership->status == 'denied') return $this->apiResponse('3058', __('Request denied'));
            }

            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $user->id,
                'role' => 'member',
                'status' => 'pending'
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
            /** @var UserAPIToken $request->api_token */
            $user = User::where('api_token', $request->api_token)->first();

            $membership = GroupMember::where('id', $request->id)->first();
            if(!$membership) return $this->apiResponse('3059', __('Unknown membership'));

            $group = Group::where('id', '=', $membership->group_id)->first();
            if(!$group) return $this->apiResponse('3060', __('Unknown group'));
            if(!isset($group->adminsRel)) return $this->apiResponse('3061', __('Admin not found'));
            if(!$group->isAdmin($user->id)) return $this->apiResponse('3062', __('No permission found'));

            if(empty($request->status)) return $this->apiResponse('3063', __('Empty status'));
            else{
                if($request->status == 'accept'){
                    $membership->update(['status' => 'accepted']);
                }else if($request->status == 'deny'){
                    $membership->update(['status' => 'denied']);
                }else{
                    return $this->apiResponse('3064', __('Unknown action'));
                }

                $group->update(['members' => GroupMember::where('group_id', $group->id)->where('status', 'accepted')->count()]);
            }

            return $this->apiResponse('0000', __('Success'));
        }catch (\Exception $e){
            return $this->apiResponse('3051', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
