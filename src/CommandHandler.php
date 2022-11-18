<?php
namespace App;

use App\Constants\Commands;
use App\Helpers\Verification;

class CommandHandler {
    static public function run(string $command, string $user_id, array $args = []): void {
        $msg = '';
        switch ($command) {
            case Commands::VERIFY:
                if (count($args) !== 0) {
                    $niu = $args[0];
                    $msg = Verification::create($user_id, $niu);
                } else {
                    $msg = 'Tienes que enviar tu NIU';
                }
                break;
            case Commands::PIN:
                if (count($args) !== 0) {
                    $pin = $args[0];
                    $msg = Verification::verify($user_id, $pin);
                } else {
                    $msg = 'Tienes que mandar tu PIN';
                }
                break;
            case Commands::RESET:
                Verification::delete($user_id);
                $msg = 'Usuario eliminado';
            default:
                $msg = 'Comando no válido';
        }

        $twitter = new Twitter;
        $twitter->reply($msg, $user_id);
    }
}
