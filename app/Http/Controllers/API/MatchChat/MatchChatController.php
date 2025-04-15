<?php

namespace App\Http\Controllers\API\MatchChat;

use App\Http\Controllers\Admin\Core\Filters;
use App\Http\Controllers\Controller;
use App\Models\Chat\MCChat;
use App\Models\Chat\MCCMessage;
use App\Models\Chat\MCCMessageLike;
use App\Models\Social\Groups\Group;
use App\Models\SystemCore\SeasonMatch;
use App\Models\SystemCore\Users\UserTeams;
use App\Models\User;
use App\Traits\Common\FileTrait;
use App\Traits\Common\LogTrait;
use App\Traits\Http\ResponseTrait;
use App\Traits\Mqtt\MqttTrait;
use http\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchChatController extends Controller{
    use ResponseTrait, LogTrait, FileTrait, MqttTrait;

    protected string $_file_path = 'files/chats/match-chats';
    protected int $_number_of_messages = 20;

    /**
     * Fetch next matches for my clubs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse{
        try{
            $teams = UserTeams::where('user_id', '=', $request->user_id)->first();
            if(!$teams) return $this->apiResponse('4201', __('User did not select any clubs'));

            $matches = SeasonMatch::where(function ($query) use ($teams) {
                $query->where('home_team', '=', $teams->team)
                    ->orWhere('home_team', '=', $teams->national_team)
                    ->orWhere('visiting_team', '=', $teams->team)
                    ->orWhere('visiting_team', '=', $teams->national_team);
            })->where('date', '>=', date('Y-m-d'))
                ->with('seasonRel.leagueRel:id,name,type,logo,country_id,gender')
                ->with('seasonRel:id,start_y,end_y,season,league_id')
                ->with('homeRel:id,name,flag,country_id,founded,national,code,gender')
                ->with('visitorRel:id,name,flag,country_id,founded,national,code,gender')
                ->with('optionsRel:value,name')
                ->with('chatRel:id,hash')
                ->get(['season_id', 'home_team', 'visiting_team', 'date', 'options', 'chat_id']);


            return $this->apiResponse('0000', __('Success'), [
                'matches' => $matches->toArray(),
                'img_path' => '/files/core/clubs/'
            ]);
        }catch (\Exception $e){
            $this->write('API: MatchChatController::add()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('4200', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Check does user have access to this chat
     * @param Request $request
     * @param $chat
     * @return bool
     */
    public function chatAccess(Request $request, $chat): bool{
        try{
            $match = SeasonMatch::where('chat_id', '=', $chat->id)->first();
            $userTeams = UserTeams::where('user_id', '=', $request->user_id)->first();

            $found = false;
            /** Club info */
            if($match->home_team == $userTeams->team) $found = true;
            if($match->visiting_team == $userTeams->team) $found = true;

            /** National team info */
            if($match->home_team == $userTeams->national_team) $found = true;
            if($match->visiting_team == $userTeams->national_team) $found = true;

            return $found;
        }catch (\Exception $e){
            $this->write('API: MatchChatController::chatAccess()', $e->getCode(), $e->getMessage(), $request);
            return false;
        }
    }

    /**
     * Save text message to chat and broadcast over sockets
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveMessage(Request $request): JsonResponse{
        try{
            if(!isset($request->message) or empty($request->message)) return $this->apiResponse('4201', __('Empty message'));
            if(!isset($request->chat_id)) return $this->apiResponse('4202', __('Unknown chat'));

            $chat = MCChat::where('id', '=', $request->chat_id)->first();
            if(!$chat) return $this->apiResponse('4203', __('Unknown chat'));

            /** Check for chat access */
            if(!$this->chatAccess($request, $chat)) return $this->apiResponse('4204', __('Do not have access to this chat'));

            $message = MCCMessage::create([
                'chat_id' => $request->chat_id,
                'user_id' => $request->user_id,
                'message' => $request->message
            ]);

            $message = MCCMessage::where('id', '=', $message->id)
                ->with('userRel:id,name,username,photo')
                ->first(['id', 'user_id', 'message', 'likes']);


            /** Broadcast over MQTT (Websockets) */
            try{
                $this->publishMatchChatMessage($chat->hash, 'message', $message);
            }catch (\Exception $e){
                $this->write('API: MatchChatController::publishMatchChatMessage()', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('4205', __('Error while publishing message'));
            }

            return $this->apiResponse('0000', __('Success'), [
                'message' => $message
            ]);
        }catch (\Exception $e){
            $this->write('API: MatchChatController::saveMessage()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('4200', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Upload photo to match-chat and broadcast over sockets
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadPhoto(Request $request): JsonResponse{
        try{
            if(!isset($request->photo)) return $this->apiResponse('4210', __('Empty photo'));
            if(!isset($request->chat_id)) return $this->apiResponse('4211', __('Unknown chat'));

            $chat = MCChat::where('id', '=', $request->chat_id)->first();
            if(!$chat) return $this->apiResponse('4212', __('Unknown chat'));

            /** Check for chat access */
            if(!$this->chatAccess($request, $chat)) return $this->apiResponse('4213', __('Do not have access to this chat'));

            $request['path'] = $this->_file_path;
            $file = $this->saveFile($request, 'photo');

            $message = MCCMessage::create([
                'chat_id' => $request->chat_id,
                'user_id' => $request->user_id,
                'file_id' => $file->id
            ]);

            $message = MCCMessage::where('id', '=', $message->id)
                ->with('userRel:id,name,username,photo')
                ->with('photoRel:id,name,ext,type,path')
                ->first(['id', 'user_id', 'message', 'file_id', 'likes']);

            /** Broadcast over MQTT (Websockets) */
            try{
                $this->publishMatchChatMessage($chat->hash, 'image', $message);
            }catch (\Exception $e){
                $this->write('API: MatchChatController::publishMatchChatMessage()', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('4205', __('Error while publishing message'));
            }

            return $this->apiResponse('0000', __('Success'), [
                'message' => $message
            ]);
        }catch (\Exception $e){
            $this->write('API: MatchChatController::uploadPhoto()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('4200', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Fetch chat messages (last n-th messages); Pagination enabled
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchMessages(Request $request): JsonResponse{
        try{
            if(!isset($request->chat_id)) return $this->apiResponse('4215', __('Unknown chat'));
            $chat = MCChat::where('id', '=', $request->chat_id)->first();
            if(!$chat) return $this->apiResponse('4216', __('Unknown chat'));
            /** Check for chat access */
            if(!$this->chatAccess($request, $chat)) return $this->apiResponse('4217', __('Do not have access to this chat'));


            if(isset($request->number)) $this->_number_of_messages = $request->number;
            $messages = MCCMessage::where('chat_id', '=', $request->chat_id);

            $messages = $messages
                ->with('userRel:id,name,username,photo')
                ->with('photoRel:id,name,ext,type,path')
                ->orderBy('id', 'DESC')
                ->select(['id', 'user_id', 'message', 'file_id', 'likes'])->orderBy('id', 'DESC');

            $messages = Filters::filter($messages, $this->_number_of_messages);

            return $this->apiResponse('0000', __('Success'), [
                'messages' => $messages->toArray()
            ]);
        }catch (\Exception $e){
            $this->write('API: MatchChatController::fetchMessages()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('4200', __('Error while processing your request. Please contact an administrator'));
        }
    }

    /**
     * Like or unlike chat message; Update number of likes of message
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function likeMessage(Request $request): JsonResponse{
        try{
            if(!isset($request->message_id)) return $this->apiResponse('4220', __('Unknown message'));

            $message = MCCMessage::where('id', '=', $request->message_id)->first();
            if(!$message) return $this->apiResponse('4221', __('Unknown message'));
            $chat = MCChat::where('id', '=', $message->chat_id)->first();
            if(!$chat) return $this->apiResponse('4222', __('Unknown chat'));

            $liked = false;

            $like = MCCMessageLike::where('message_id', '=', $request->message_id)->where('user_id', '=', $request->user_id)->first();
            if(!$like){
                /* Create new like sample */
                MCCMessageLike::create([
                    'message_id' => $request->message_id,
                    'user_id' => $request->user_id
                ]);

                $liked = true;
            }else{
                /* Remove like sample */
                MCCMessageLike::where('message_id', '=', $request->message_id)->where('user_id', '=', $request->user_id)->delete();
            }
            /* Get total likes of comment */
            $totalLikes = MCCMessageLike::where('message_id', '=', $request->message_id)->count();

            /* Update number of likes of comment */
            $message->update([
                'likes' => $totalLikes
            ]);

            /** Broadcast over MQTT (Websockets) */
            try{
                $this->publishMatchChatMessage($chat->hash, 'like', [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'liked' => $liked,
                    'totalLikes' => $totalLikes
                ]);
            }catch (\Exception $e){
                $this->write('API: MatchChatController::publishMatchChatMessage()', $e->getCode(), $e->getMessage(), $request);
                return $this->apiResponse('4205', __('Error while publishing message'));
            }

            return $this->apiResponse('0000', __('Success'), [
                'liked' => $liked,
                'totalLikes' => $totalLikes
            ]);
        }catch (\Exception $e){
            $this->write('API: MatchChatController::fetchMessages()', $e->getCode(), $e->getMessage(), $request);
            return $this->apiResponse('4200', __('Error while processing your request. Please contact an administrator'));
        }
    }
}
