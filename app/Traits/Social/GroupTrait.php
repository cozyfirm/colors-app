<?php

namespace App\Traits\Social;

use App\Models\Social\Groups\Group;
use App\Models\Social\Groups\GroupMember;
use App\Traits\Common\FileTrait;
use App\Traits\Http\ResponseTrait;
use Illuminate\Http\Request;

trait GroupTrait{
    use FileTrait, ResponseTrait;
    /**
     * Check if user has permission to specific group
     *
     * @param Request $request
     * @return bool
     */
    protected function userHasPermissionToGroup(Request $request): bool{
        try{
            $group = Group::where('id', '=', $request->group_id)->first();
            /** If group does not exist, return false immediately */
            if(!$group) return false;

            /** If group is private, check for permissions */
            if(!$group->public){

            }

            /** Membership must exists; For public groups, user has to click join!! */
            $membership = GroupMember::where('group_id', '=', $request->group_id)->where('user_id', '=', $request->user_id)->first();
            if(!$membership) return false;
            else{
                if($membership->status != 'accepted') return false;
            }
            return true;
        }catch (\Exception $e){ return false; }
    }
}
