<?php
namespace App\Commands;

use App\Constants\Messages;
use App\Constants\Values;
use App\Helpers\Misc;
use App\Items\Content;

class PollCommand extends BaseCommand {
    protected int $minArgs = 3;
    protected int $maxArgs = 5;

    public function run(array $args = []): string {
        if (!$this->hasMinArgs($args) || $this->overflowArgs($args)) {
            return Messages::POLL_ERROR_PARAMS;
        }
        $msg = $args[0];
        if (strlen($msg) > Values::MESSAGE_MAX_CHARS) {
            return Messages::POLL_ERROR_MSG;
        }
        array_shift($args);

        $duration = $args[0];
        if (!(is_numeric($duration) && intval($duration) <= Values::POLL_MAX_DURATION)) {
            return Messages::POLL_ERROR_DURATION;
        }

        // Check if options do not overflow max characters
        $invalidOptions = array_filter($args, fn($item) => strlen($item) > Values::POLL_MAX_CHARS);
        if (!empty($invalidOptions)) {
            return Messages::POLL_ERROR_OPTION;
        }

        $moderate = Misc::env('APP_MODERATION', true);
        $contentDb = new Content();
        $contentDb->add($msg, $this->user_id, !$moderate, [
            'type' => 'poll',
            'data' => implode(';', $args)
        ]);
        $res = sprintf(Messages::POLL_SENT, $moderate ? 'Sí' : 'No');
        return $res;
    }
}
