<?php
namespace App;

use App\Helpers\Misc;

class Twitter {
    private \Abraham\TwitterOAuth\TwitterOAuth $client;

    function __construct() {
        $consumer_key = Misc::env('CONSUMER_KEY', '');
        $consumer_secret = Misc::env('CONSUMER_SECRET', '');
        $access_token = Misc::env('ACCESS_TOKEN', '');
        $access_token_secret = Misc::env('ACCESS_TOKEN_SECRET', '');

        $this->client = new \Abraham\TwitterOAuth\TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    }

    public function getClient(): \Abraham\TwitterOAuth\TwitterOAuth {
        return $this->client;
    }

    public function ok(): bool {
        $code = $this->client->getLastHttpCode();
        return (200 <= $code) && ($code <= 299);
    }

    public function me(): object {
        return $this->client->get('account/verify_credentials');
    }

    public function publish(string $msg, ?string $reply_id = null, ?string $media_id = null, ?array $poll = null): object {
        $this->client->setApiVersion('2');
        $query = [
            'text' => $msg
        ];

        if ($media_id) {
            $query['media'] = [
                'media_ids' => [$media_id]
            ];
        }

        if ($reply_id) {
            $query['reply'] = [
                'in_reply_to_tweet_id' => $reply_id
            ];
        }

        if ($poll) {
            $duration = $poll[0];
            array_shift($poll);
            $query['poll'] = [
                'duration_minutes' => $duration,
                'options' => $poll
            ];
        }

        $out = $this->client->post('tweets', $query, true);
        $this->client->setApiVersion('1.1');
        return $out;
    }

    public function reply(string $msg, string $recipient_id, ?string $tweet_id = null) {
        $final_msg = $msg;
        $bot_name = Misc::env('BOT_NAME', 'umabot_ng');
        if ($tweet_id) {
            $final_msg .= " https://twitter.com/{$bot_name}/status/{$tweet_id}";
        }
        $this->client->post('direct_messages/events/new', [
            'event' => [
                'type' => 'message_create',
                'message_create' => [
                    'target' => [
                        'recipient_id' => $recipient_id
                    ],
                    'message_data' => [
                        'text' => $final_msg
                    ]
                ]
            ]
        ], true);
    }

    public function uploadFromPath(string $path, string $type): object {
        $category = '';
        switch($type) {
            case 'video':
                $category = 'video';
                break;
            case 'photo':
                $category = 'image';
                break;
        }
        $out = $this->client->upload('media/upload', [
            'media' => $path,
            'media_category' => 'tweet_' . $category
        ], true);
        return $out;
    }

    public function checkUpload(string $media_id): object {
        return $this->client->mediaStatus($media_id);
    }
}
