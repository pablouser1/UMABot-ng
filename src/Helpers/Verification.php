<?php
namespace App\Helpers;

use App\Db;
use App\Mail;

class Verification {
    const DOMAIN = '@uma.es';

    /**
     * Create and send pin to user
     */
    static public function create(string $user_id, string $niu) {
        $mail = new Mail;
        $db = new Db;

        $pin = $db->createPin($user_id, $niu);
        $mail->sendCode($niu . self::DOMAIN, $pin);
    }

    /**
     * Check if pin sent is valid
     */
    static public function verify(string $user_id, string $pin): bool {
        $db = new Db;
        $possible_user = $db->getPin($pin);

        if ($possible_user && $possible_user->user_id === $user_id) {
            $db->deletePin($possible_user->id);
            $db->addUser($user_id, $possible_user->niu);
            return true;
        }
        return false;
    }
}