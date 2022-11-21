<?php
namespace App\Helpers;

use App\Constants\Links;

class Wrappers {
    static public function db(): \PDO {
        $driver = Misc::env('DB_DRIVER', 'mysql');
        $host = Misc::env('DB_HOST', 'localhost');
        $port = Misc::env('DB_PORT', 3306);
        $db = Misc::env('DB_NAME', 'umabot');
        $username = Misc::env('DB_USERNAME', '');
        $password = Misc::env('DB_PASSWORD', '');
        $dns = $driver .
        ':host=' . $host .
        ';port=' . $port .
        ';dbname=' . $db . ';charset=utf8mb4';
        return new \PDO($dns, $username, $password);
    }

    /**
     * Render template with Plates
     */
    static public function plates(string $view, array $data = []) {
        $engine = new \League\Plates\Engine(__DIR__ . '/../../templates/');
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
}