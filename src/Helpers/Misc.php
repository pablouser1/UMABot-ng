<?php
namespace App\Helpers;

use Abraham\TwitterOAuth\Consumer;
use Abraham\TwitterOAuth\HmacSha1;
use Abraham\TwitterOAuth\Request;
use Abraham\TwitterOAuth\Token;
use App\Constants\Links;

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

    /**
     * Render template with Plates
     */
    static public function plates(string $view, array $data = []) {
        $engine = new \League\Plates\Engine(__DIR__ . '/../../templates/');
        $engine->loadExtension(new \League\Plates\Extension\Asset(__DIR__ . '/../../public', true));
        $engine->registerFunction('url', function(string $endpoint, array $params = []): string {
            $path = $endpoint;
            if (!empty($params)) {
                $path .= '?' . http_build_query($params);
            }
            return Misc::url($path);
        });
        $engine->registerFunction('links', function (): array {
            return Links::list;
        });
        $engine->registerFunction('isAdmin', function (): bool {
            return Misc::isLoggedIn();
        });
        $engine->registerFunction('contact', function (): string {
            return Misc::contact();
        });
        $engine->registerFunction('botName', function (): string {
            return Misc::env('BOT_NAME', 'umabot_ng');
        });
        $template = $engine->make($view);
        echo $template->render($data);
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
