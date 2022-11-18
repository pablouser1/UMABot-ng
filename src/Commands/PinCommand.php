<?php
namespace App\Commands;

use App\Helpers\Verification;

class PinCommand extends BaseCommand {
    protected int $minArgs = 1;

    public function run(array $args = []): string {
        if (!$this->isValid($args)) {
            return "Tienes que escribir un PIN";
        }

        $niu = $args[0];
        $msg = Verification::create($this->user_id, $niu);
        return $msg;
    }
}
