<?php
namespace App\Controllers;

use App\CommandHandler;
use App\Helpers\Challenge;
use App\Helpers\Misc;
use App\MessageHandler;

class HookController {
    static public function get() {
        if (isset($_GET['crc_token'])) {
            echo Challenge::register($_GET['crc_token']);
        }
    }

    static public function post() {
        if (isset($_SERVER["HTTP_X_TWITTER_WEBHOOKS_SIGNATURE"]) && Challenge::valid($_SERVER["HTTP_X_TWITTER_WEBHOOKS_SIGNATURE"])) {
            $eventJSON = file_get_contents('php://input');
            $event = json_decode($eventJSON);
            if (isset($event->direct_message_events) && $event->direct_message_events[0]->type === 'message_create') {
                $message_create = $event->direct_message_events[0]->message_create;
                $message_data = $message_create->message_data;
                $user_id = $message_create->sender_id;
                // Check first that message creator isn't the bot itself
                if ($user_id !== Misc::env('BOT_ID', '')) {
                    $msg = trim($message_data->text);
                    if ($msg[0] === '/') {
                        $arguments = explode(' ', $msg);
                        $command = ltrim($arguments[0], '/');
                        $args = [];
                        if (count($arguments) > 0) {
                            $args = array_slice($arguments, 1);
                        }
                        CommandHandler::run($command, $user_id, $args);
                    } else {
                        // Try to add message
                        $media = null;
                        if (isset($message_data->attachment) && $message_data->attachment->type === 'media') {
                            $media = $message_data->attachment->media;
                            // Strip url
                            $msg = str_replace($media->url, "", $msg);
                        }
                        MessageHandler::run($msg, $user_id, $media);
                    }
                }
            }
        }
    }
}
