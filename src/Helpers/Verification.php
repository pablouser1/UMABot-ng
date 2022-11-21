<?php
namespace App\Helpers;

use App\Items\Pin;
use App\Items\User;
use App\Mail;

class Verification {
    const DOMAIN = '@uma.es';

    /**
     * Create and send pin to user
     */
    static public function create(string $user_id, string $niu): string {
        // https://www.uma.es/servicio-central-de-informatica/cms/menu/catalogo/mensajeria-electronica/
        if (substr($niu, 0, 3) !== '061') {
            return 'NIU Inválido';
        }
        $db = Wrappers::db();
        $pinDb = new Pin($db);
        $userDb = new User($db);

        $mail = new Mail;
        // Check if user is already verified
        if ($userDb->isTwitterVerified($user_id)) {
            return '¡Ya estás verificado!';
        }

        if ($userDb->isNiuVerified($niu)) {
            return '¡Ese NIU ya está en uso!';
        }

        $address = $niu . self::DOMAIN;
        $pin = Misc::randomNumber(6);
        $sent = $mail->sendCode($address, $pin);
        if ($sent) {
            if ($pinDb->hasUserSent($user_id, $niu)) {
                $pinDb->update($user_id, $niu, $pin);
            } else {
                $pinDb->add($user_id, $niu, $pin);
            }
            return 'Si ese NIU realmente existe, debes haber recibido un correo electrónico en tu correo corporativo (@uma.es) con más instrucciones. Si no has recibido el correo comprueba que el NIU sea válido o que tu bandeja de entrada no esté llena';
        } else {
            return 'Error al enviar el correo electrónico, por favor inténtalo más tarde';
        }
    }

    static public function delete(string $user_id) {
        $user = new User;
        $user->delete($user_id);
        return "Usuario eliminado";
    }

    /**
     * Check if pin sent is valid
     */
    static public function verify(string $user_id, string $pin): string {
        $db = Wrappers::db();
        $userDb = new User($db);
        $pinDb = new Pin($db);

        $possible_user = $pinDb->get($pin);

        if ($possible_user && $possible_user->user_id === $user_id) {
            $pinDb->delete($possible_user->id);
            $userDb->add($user_id, $possible_user->niu);
            return "¡Tu cuenta ha sido verificada! Ya puedes mandar mensajes";
        }
        return "PIN Inválido";
    }
}
