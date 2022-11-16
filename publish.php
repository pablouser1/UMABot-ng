<?php
// Executed by Cron/Systemd each ~15min
require 'vendor/autoload.php';
require 'bootstrap.php';

use App\Db;
use App\Helpers\Media;
use App\Twitter;

$db = new Db;

$content = $db->getPublishable();

// Check if there is at least one item waiting to be published
if ($content) {
    $twitter = new Twitter;
    $media_id = null;
    if ($content->media_id) {
        $media = new Media;
        $path = $media->download($content->media_url);
        $upload = $twitter->uploadFromPath($path, $content->type);
        if (!$twitter->ok()) {
            $twitter->reply('Ha habido un error al enviar tu tweet: No se pudo subir el archivo multimedia', $content->user_id);
            exit;
        }

        $media_id = $upload->media_id_string;
        // Continue when media is processed
        $check_after_secs = $upload->processing_info->check_after_secs;
        $finished = false;
        while (!$finished) {
            sleep($check_after_secs);
            $process = $twitter->checkUpload($media_id);
            if ($process->processing_info->state === 'succeeded') {
                $finished = true;
            } else {
                $check_after_secs = $process->processing_info->check_after_secs;
            }
        }
        // Media finished processing, make cleanup
        $media->cleanup($path);
    }

    // Split message on 280 chunks, with wordwrap and send tweet(s)
    $msgs_split = explode("\n", wordwrap($content->msg, 280));
    $reply_id = null;
    foreach ($msgs_split as $i => $msg_split) {
        // Add media only on first tweet
        $tweet = $twitter->publish($msg_split, $i === 0 ? $media_id : null, $reply_id);
        if (!$twitter->ok()) {
            if (isset($tweet->detail)) {
                $twitter->reply('Ha habido un error al enviar tu tweet: ' . $tweet->detail, $content->user_id);
            }
            exit;
        }
        $reply_id = $tweet->data->id;
    }
    $db->setContentPublished($content->id);
    $twitter->reply('¡Enhorabuena! Tu mensaje ha sido publicado con éxito', $content->user_id, $tweet->data->id);
}
