<?php
namespace App;

use App\Constants\FilterTypes;
use App\Constants\MessageTypes;
use App\Helpers\Misc;
use App\Helpers\Wrappers;
use App\Items\Content;
use App\Items\User;

class MessageHandler {
    static public function run(string $msg, string $user_id, ?object $media = null) {
        $res = '';
        $db = Wrappers::db();
        $userDb = new User($db);
        $contentDb = new Content($db);

        $twitter = new Twitter;
        // Check if user is allowed to send stuff first
        if (!Misc::env('APP_VERIFICATION', true) || $userDb->isTwitterVerified($user_id)) {
            $attachment = [];
            // Handle media
            if ($media) {
                $attachment['type'] = $media->type;

                switch ($attachment['type']) {
                    case MessageTypes::VIDEO:
                        $attachment['data'] = $media->video_info->variants[0]->url;
        
                        $found = false;
                        $i = 0;
                        while (!$found) {
                            if (isset($media->video_info->variants[$i]->bitrate) && $media->video_info->variants[$i]->content_type !== "application/x-mpegURL") {
                                $found = true;
                                $attachment['data'] = $media->video_info->variants[$i]->url;
                            }
                            $i++;
                        }
                        break;
                    case MessageTypes::PHOTO:
                        $attachment['data'] = $media->media_url_https;
                        break;
                    default:
                        $media = null;
                }
            }
            $moderate = Misc::env('APP_MODERATION', true);
            $success = $contentDb->add($msg, $user_id, !$moderate, $attachment);
            if ($success) {
                if ($moderate) {
                    $position = $contentDb->queue(FilterTypes::WAITING);
                    $res = '¡Tu mensaje ha sido agregado a la cola de moderación con éxito! Posición: ' . $position . '. Se publicará cuando sea aprobado por un moderador';
                } else {
                    $position = $contentDb->queue(FilterTypes::APPROVED);
                    $twitter->reply('Tu mensaje ha sido agregado a la cola para ser agregado! Posición: ' . $position, $user_id);
                }
            } else {
                $res = 'Ha habido un error al registrar tu mensaje, por favor inténtalo de nuevo más tarde';
            }
        } else {
            $howto = Misc::url('/howto');
            $res = "No tienes la cuenta verificada para poder enviar mensajes, más información en: {$howto}";
        }

        $twitter->reply($res, $user_id);
    }
}
