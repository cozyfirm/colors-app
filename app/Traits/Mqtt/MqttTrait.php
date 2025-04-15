<?php

namespace App\Traits\Mqtt;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpMqtt\Client\Facades\MQTT;

trait MqttTrait{

    /**
     * @return void
     * Get an IP ADDR from HTTP Request
     */
    public function publishMessage($type, $topic, $code, $data, $uri = null): void{
        try{
            $message = [
                'type' => $type,
                'code' => $code,
                'data' => $data,
                'uri' => $uri
            ];

            MQTT::publish($topic, json_encode($message, JSON_UNESCAPED_UNICODE ));
        }catch (\Exception $e){ dd($e); throw $e; }
    }

    /**
     * @param $chat -> Topic
     * @param User | Auth $sender -> User object that sends message
     * @param $message -> Message
     * @return void
     */
    public function publishChatMessage($chat, User | Auth $sender, $message): void{
        try{
            $message = [
                'code' => '2000',
                'data' => [
                    'sender' => $sender,
                    'message' => $message
                ]
            ];

            MQTT::publish($chat, json_encode($message, JSON_UNESCAPED_UNICODE ));
        }catch (\Exception $e){ throw $e; }
    }

    /**
     * @param $topic - APi token of user
     * @param User|Auth $sender - User that sends notification
     * @param $code
     * @param $message
     * @return void
     *
     * Codes of messages:
     *
     *  1. 2010: New message from user
     */
    public function pushNotification($topic, User | Auth $sender, $code, $message): void{
        try{
            /* Remove api_token */
            $sender->api_token = time();

            $message = [
                'code' => $code,
                'data' => [
                    'sender' => $sender,
                    'message' => $message
                ]
            ];

            MQTT::publish($topic, json_encode($message, JSON_UNESCAPED_UNICODE ));
        }catch (\Exception $e){ throw $e; }
    }

    /** ------------------------------------------------------------------------------------------------------------- */
    /**
     * Publish Match-Chat message to all connected users; Two types:
     *      1. message
     *      2. image
     *
     * @param $chat
     * @param $type
     * @param $message
     * @return void
     */
    public function publishMatchChatMessage($chat, $type, $message): void{
        try{
            $message = [
                'type' => $type,
                'message' => $message
            ];

            MQTT::publish($chat, json_encode($message, JSON_UNESCAPED_UNICODE ));
        }catch (\Exception $e){ dd($e); throw $e; }
    }

}
