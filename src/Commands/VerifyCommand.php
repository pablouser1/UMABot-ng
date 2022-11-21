<?php
namespace App\Commands;

use App\Helpers\Verification;

class VerifyCommand extends BaseCommand {
    protected int $minArgs = 1;

    public function run(array $args = []): string {
        if (!$this->hasMinArgs($args)) {
            return "Tienes que escribir un NIU";
        }

        $pin = $args[0];
        $msg = Verification::create($this->user_id, $pin);
        return $msg;
    }
}
