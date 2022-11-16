<?php
namespace App;

use App\Constants\Commands;
use App\Helpers\Verification;

class CommandHandler {
    static public function run(string $command, string $user_id, array $args = []): void {
        $msg = '';
        switch ($command) {
            case Commands::VERIFY:
                $niu = $args[0];
                Verification::create($user_id, $niu);
                $msg = 'Si ese NIU realmente existe, debes haber recibido un correo electrónico con más instrucciones';
                break;
                /*
            case Commands::UNVERIFY:
                // TODO
                break;
                */
            case Commands::PIN:
                $pin = $args[0];
                $valid = Verification::verify($user_id, $pin);
                if ($valid) {
                    $msg = "¡Tu cuenta ha sido verificada! Ya puedes mandar mensajes";
                } else {
                    $msg = "PIN inválido";
                }
                break;
            default:
                $msg = 'Comando no válido';
        }

        $twitter = new Twitter;
        $twitter->reply($msg, $user_id);
    }
}