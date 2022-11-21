<?php
namespace App\Commands;

use App\Helpers\Verification;

class ResetCommand extends BaseCommand {
    public function run(array $args = []): string {
        $msg = Verification::delete($this->user_id);
        return $msg;
    }
}
