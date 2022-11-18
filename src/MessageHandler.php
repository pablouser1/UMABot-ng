<?php
namespace App;

use App\Constants\MessageTypes;
use App\Helpers\Misc;

class MessageHandler {
    static public function run(string $msg, string $user_id, ?object $media = null) {
        $res = '';
        $db = new Db;
        $twitter = new Twitter;
        // Check if user is allowed to send stuff first
        if (!Misc::env('APP_VERIFICATION', true) || $db->isUserVerified($user_id)) {
            $type = 'text';
            $media_id = null;
            $media_url = null;
            // Handle media
            if ($media) {
                $type = $media->type;
                $media_id = $media->id_str;

                switch ($type) {
                    case MessageTypes::VIDEO:
                        $media_url = $media->video_info->variants[0]->url;
        
                        $found = false;
                        $i = 0;
                        while (!$found) {
                            if (isset($media->video_info->variants[$i]->bitrate) && $media->video_info->variants[$i]->content_type !== "application/x-mpegURL") {
                                $found = true;
                                $media_url = $media->video_info->variants[$i]->url;
                            }
                            $i++;
                        }
                        break;
                    case MessageTypes::PHOTO:
                        $media_url = $media->media_url_https;
                        break;
                    default:
                        $media = null;
                }
            }
            $moderate = Misc::env('APP_MODERATION', true);
            $success = $db->addContent($msg, $user_id, $media_id, $media_url, $type, !$moderate);
            if ($success) {
                if ($moderate) {
                    $position = $db->getModerationQueue();
                    $res = '¡Tu mensaje ha sido agregado a la cola de moderación con éxito! Posición: ' . $position . '. Se publicará cuando sea aprobado por un moderador';
                } else {
                    $position = $db->getContentQueue();
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
