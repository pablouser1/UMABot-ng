<?php
namespace App\Controllers;

use App\Db;
use App\Helpers\Misc;

class HomeController {
    static public function get() {
        $db = new Db;
        $stats = new \stdClass;
        $stats->content = $db->getContentQueue();
        $stats->moderation = $db->getModerationQueue();
        $stats->total = $db->getContentTotal();

        $instance = new \stdClass;
        $instance->moderation = Misc::env('APP_MODERATION', true);
        $instance->verification = Misc::env('APP_VERIFICATION', true);
        Misc::plates('home', [
            'stats' => $stats,
            'instance' => $instance
        ]);
    }
}
