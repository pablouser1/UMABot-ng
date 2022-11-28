<?php
namespace App\Controllers;

use App\CommandHandler;
use App\Constants\Messages;
use App\Helpers\Challenge;
use App\Helpers\Misc;
use App\MessageHandler;
use App\Twitter;

class HookController {
    static public function get() {
        if (isset($_GET['crc_token'])) {
            echo Challenge::register($_GET['crc_token']);
        }
    }

    static public function post() {
        if (self::__isValidSignature()) {
            $eventJSON = file_get_contents('php://input');
            $event = json_decode($eventJSON);
            if (isset($event->direct_message_events) && $event->direct_message_events[0]->type === 'message_create') {
                $message_create = $event->direct_message_events[0]->message_create;
                $message_data = $message_create->message_data;
                $user_id = $message_create->sender_id;
                // Check first that message creator isn't the bot itself
                if ($user_id !== Misc::env('BOT_ID', '')) {
                    // Stop if maintenance
                    if (Misc::env('APP_MAINTENANCE', false)) {
                        $twitter = new Twitter;
                        $twitter->reply(Messages::MISC_MAINTENANCE, $user_id);
                        exit;
                    }
                    $msg = trim($message_data->text);
                    if ($msg[0] === '/') {
                        // Is command
                        $payload = [];
                        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $msg, $payload); // https://stackoverflow.com/a/2202489
                        $payload_trimmed = array_map(fn($item) => trim($item, '"'), $payload[0]); // Remove '"'
                        $command = ltrim($payload_trimmed[0], '/');
                        array_shift($payload_trimmed);
                        CommandHandler::run($command, $user_id, $payload_trimmed);
                    } else {
                        // Is message
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

    static private function __isValidSignature(): bool {
        // return isset($_SERVER["HTTP_X_TWITTER_WEBHOOKS_SIGNATURE"]) && Challenge::valid($_SERVER["HTTP_X_TWITTER_WEBHOOKS_SIGNATURE"]);
        return true;
    }
}
