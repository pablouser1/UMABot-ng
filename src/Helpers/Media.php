<?php

namespace App\Helpers;

use App\Constants\UserAgents;

/**
 * Handles streaming/download from Twitter
 * Before sending the request we sign it using OAuth (required for photos)
 */
class Media {
    const BUFFER_SIZE = 256 * 1024;

    // Headers to forward back to client, to be filled with response header values from Twitter
    private array $headers_to_forward = [
        'Content-Type' => null,
        'Content-Length' => null,
        'Content-Range' => null,
        // Always send this one to explicitly say we accept ranged requests
        'Accept-Ranges' => 'bytes'
    ];

    static public function download(string $url): ?string {
        $temp_path = sys_get_temp_dir();
        $authorization = Misc::signReq($url, 'GET');
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [$authorization],
            CURLOPT_BUFFERSIZE => self::BUFFER_SIZE,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT => UserAgents::DEFAULT
        ]);

        $response = curl_exec($ch);

        $extension = '';
        $finfo = new \finfo(FILEINFO_MIME);
        $mime_info = $finfo->buffer($response);

        $mime = explode(';', $mime_info)[0];

        switch ($mime) {
            case 'video/mp4':
                $extension = 'mp4';
                break;
            case 'image/jpeg':
                $extension = 'jpg';
                break;
        }
        $filename = uniqid('vid_') . '.' . $extension;
        $path = $temp_path . '/' . $filename;
        file_put_contents($path, $response);

        return $path;
    }

    public function cleanup(string $path) {
        if (file_exists($path) && is_writable($path)) {
            unlink($path);
        }
    }

    public function stream(string $url) {
        $authorization = Misc::signReq($url, 'GET');
        $ch = curl_init($url);

        $headers_to_send = [$authorization];
        if (isset($_SERVER['HTTP_RANGE'])) {
            $headers_to_send[] = 'Range: ' . $_SERVER['HTTP_RANGE'];
            http_response_code(206);
        }

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers_to_send,
            CURLOPT_BUFFERSIZE => self::BUFFER_SIZE,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT => UserAgents::DEFAULT,
            CURLOPT_HEADERFUNCTION => function ($curl, $header) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                $header_key = ucwords(trim($header[0]), '-');
                if (array_key_exists($header_key, $this->headers_to_forward)) {
                    $header_value = trim($header[1]);
                    $this->headers_to_forward[$header_key] = $header_value;
                }
                return $len;
            }
        ]);

        $response = curl_exec($ch);
        foreach ($this->headers_to_forward as $header_key => $header_value) {
            if ($header_value != null) {
                header($header_key . ': ' . $header_value);
            }
        }
        echo $response;
        curl_close($ch);
    }
}
