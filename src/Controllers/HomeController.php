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
        Misc::plates('home', [
            'stats' => $stats
        ]);
    }
}
