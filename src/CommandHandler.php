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
                    $created = Verification::create($user_id, $niu);
                    if ($created) {
                        $msg = 'Si ese NIU realmente existe, debes haber recibido un correo electrónico en tu correo corporativo (@uma.es) con más instrucciones';
                    } else {
                        $msg = 'Ha habido un error al mandar tu PIN, quizás hay un problema de conexión o el NIU es inválido';
                    }
                } else {
                    $msg = 'Tienes que enviar tu NIU';
                }
                break;
            case Commands::PIN:
                if (count($args) !== 0) {
                    $pin = $args[0];
                    $valid = Verification::verify($user_id, $pin);
                    if ($valid) {
                        $msg = "¡Tu cuenta ha sido verificada! Ya puedes mandar mensajes";
                    } else {
                        $msg = "PIN inválido";
                    }
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
