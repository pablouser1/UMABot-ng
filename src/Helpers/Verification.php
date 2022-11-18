<?php
namespace App\Helpers;

use App\Db;
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

        $db = new Db;
        $mail = new Mail;
        // Check if user is already verified
        if ($db->isUserVerified($user_id)) {
            return '¡Ya estás verificado!';
        }

        if ($db->isNiuVerified($niu)) {
            return '¡Ese NIU ya está en uso!';
        }

        $address = $niu . self::DOMAIN;
        $pin = Misc::randomNumber(6);
        $sent = $mail->sendCode($address, $pin);
        if ($sent) {
            if ($db->hasUserSentPin($user_id, $niu)) {
                $db->updatePin($user_id, $niu, $pin);
            } else {
                $db->addPin($user_id, $niu, $pin);
            }
            return 'Si ese NIU realmente existe, debes haber recibido un correo electrónico en tu correo corporativo (@uma.es) con más instrucciones. Si no has recibido el correo comprueba que el NIU sea válido o que tu bandeja de entrada no esté llena';
        } else {
            return 'Error al enviar el correo electrónico, por favor inténtalo más tarde';
        }
    }

    static public function delete(string $user_id) {
        $db = new Db;
        $db->deleteUser($user_id);
    }

    /**
     * Check if pin sent is valid
     */
    static public function verify(string $user_id, string $pin): string {
        $db = new Db;
        $possible_user = $db->getPin($pin);

        if ($possible_user && $possible_user->user_id === $user_id) {
            $db->deletePin($possible_user->id);
            $db->addUser($user_id, $possible_user->niu);
            return "¡Tu cuenta ha sido verificada! Ya puedes mandar mensajes";
        }
        return "PIN Inválido";
    }
}
