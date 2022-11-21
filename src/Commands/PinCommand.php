<?php
namespace App\Commands;

use App\Helpers\Verification;

class PinCommand extends BaseCommand {
    protected int $minArgs = 1;

    public function run(array $args = []): string {
        if (!$this->hasMinArgs($args)) {
            return "Tienes que escribir un PIN";
        }

        $pin = $args[0];
        $msg = Verification::verify($this->user_id, $pin);
        return $msg;
    }
}
