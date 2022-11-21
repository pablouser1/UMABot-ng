<?php
namespace App\Helpers;

use Abraham\TwitterOAuth\Consumer;
use Abraham\TwitterOAuth\HmacSha1;
use Abraham\TwitterOAuth\Request;
use Abraham\TwitterOAuth\Token;

class Misc {
    static public function env(string $key, $default_value) {
        return $_ENV[$key] ?? $default_value;
    }

    static public function url(string $endpoint = ''): string {
        return self::env('APP_URL', '') . $endpoint;
    }

    static public function contact(): string {
        return self::env('APP_CONTACT', self::url('/'));
    }

    static public function redirect(string $path) {
        $location = Misc::url($path);
        header("Location: $location");
    }

    static public function isLoggedIn(): bool {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1;
    }

    static public function randomNumber($digits = 4){
        $i = 0;
        $pin = "";
        while($i < $digits){
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    /**
     * Manually signs request
     * @return string Authorization Header
     */
    static public function signReq(string $url, string $method): string {
        $consumer_key = self::env('CONSUMER_KEY', '');
        $consumer_secret = self::env('CONSUMER_SECRET', '');
        $access_token = self::env('ACCESS_TOKEN', '');
        $access_token_secret = self::env('ACCESS_TOKEN_SECRET', '');
        
        $consumer = new Consumer($consumer_key, $consumer_secret);
        $token = new Token($access_token, $access_token_secret);
        $signing = new HmacSha1();
        $request = Request::fromConsumerAndToken(
            $consumer,
            $token,
            $method,
            $url
        );
        $request->signRequest(
            $signing,
            $consumer,
            $token
        );
        $authorization = $request->toHeader();
        return $authorization;
    }
}
