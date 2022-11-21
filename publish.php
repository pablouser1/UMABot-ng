<?php
// Executed by Cron/Systemd each ~15min
require 'vendor/autoload.php';
require 'bootstrap.php';

use App\Constants\StatusTypes;
use App\Helpers\Media;
use App\Items\Content;
use App\Twitter;

$contentDb = new Content();
$content = $contentDb->getPublishable();

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
        // Wait if media needs to process
        if (isset($upload->processing_info)) {
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
        }
        // Media finished processing, make cleanup
        $media->cleanup($path);
    }

    // Split message on 280 chunks, with wordwrap and send tweet(s)
    $msgs_split = explode("<br>", wordwrap($content->msg, 280, '<br>'));
    $first_tweet = null;
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
        if ($i === 0) $first_tweet = $tweet->data->id; // Get first tweet id
        $reply_id = $tweet->data->id;
    }
    $contentDb->updateState($content->id, StatusTypes::PUBLISHED);
    $twitter->reply('¡Enhorabuena! Tu mensaje ha sido publicado con éxito', $content->user_id, $first_tweet);
}
