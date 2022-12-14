<?php
namespace App\Controllers;

use App\Helpers\Media;
use App\Helpers\Misc;

class StreamController {
    const VALID_DOMAINS = [
        "twimg.com", "twitter.com"
    ];

    static private function isValidDomain(string $url) {
        $host = parse_url($url, PHP_URL_HOST);
        $host_split = explode('.', $host);
        $host_count = count($host_split);
        if ($host_count === 3) {
            return in_array($host_split[1] . '.' . $host_split[2], self::VALID_DOMAINS);
        }
        return false;
    }

    static private function checkUrl() {
        if (!isset($_GET['url'])) {
            die('Necesitas enviar una URL');
        }

        if (!filter_var($_GET['url'], FILTER_VALIDATE_URL) || !self::isValidDomain($_GET['url'])) {
            die('URL no válida');
        }

    }

    static public function get() {
        if (!Misc::isLoggedIn()) {
            die("Necesitas iniciar sesión");
        }

        if (isset($_GET['url'])) {
            $url = $_GET['url'];
            self::checkUrl();
            $media = new Media;
            $media->stream($url);
        }
    }
}
