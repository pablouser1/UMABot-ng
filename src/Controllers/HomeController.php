<?php
namespace App\Controllers;

use App\Constants\FilterTypes;
use App\Helpers\Misc;
use App\Helpers\Wrappers;
use App\Items\Content;

class HomeController {
    static public function get() {
        $contentDb = new Content();
        $stats = new \stdClass;
        $stats->content = $contentDb->queue(FilterTypes::APPROVED);
        $stats->moderation = $contentDb->queue(FilterTypes::WAITING);
        $stats->total = $contentDb->queue(FilterTypes::ALL);

        $instance = new \stdClass;
        $instance->moderation = Misc::env('APP_MODERATION', true);
        $instance->verification = Misc::env('APP_VERIFICATION', true);
        Wrappers::plates('home', [
            'stats' => $stats,
            'instance' => $instance
        ]);
    }
}
