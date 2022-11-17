<?php
namespace App;

use App\Helpers\Misc;
use PHPMailer\PHPMailer\PHPMailer;

class Mail {
    private PHPMailer $client;

    function __construct() {
        $host = Misc::env('MAIL_HOST', '');
        $port = Misc::env('MAIL_PORT', 465);
        $username = Misc::env('MAIL_USERNAME', '');
        $password = Misc::env('MAIL_PASSWORD', '');

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $port;
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $this->client = $mail;
    }


    public function sendCode(string $destionation, string $pin): bool {
        $this->client->setFrom($this->client->Username, 'UMABot');
        $this->client->addAddress($destionation);
        $this->client->Subject = "Código de verificación de UMABot";
        $this->client->Body = $this->__html($pin);
        $this->client->AltBody = $this->__plain($pin);
        $success = $this->client->send();
        return $success;
    }

    private function __plain(string $pin): string {
        $howto = Misc::url('/howto');
        $contact = Misc::contact();
        $plain = <<<EOD
        ¡Bienvenido a UMABot-ng!
        Verifica tu cuenta con este código: {$pin}
        Si tienes problemas para verificar tu cuenta puedes consultar la guía de instalación aquí: {$howto} o contactar con la administración aquí: {$contact}
        EOD;
        return $plain;
    }

    private function __html(string $pin): string {
        $howto = Misc::url('/howto');
        $contact = Misc::contact();
        $html = <<<EOD
        <p>Bienvenido a UMABot-ng!</p>
        <p>Verifica tu cuenta con este código: <b>{$pin}</b><p>
        <p>
            Si tienes problemas para verificar tu cuenta puedes consultar la guía de instalación <a href="{$howto}">aquí</a>
        </p>
        <p>
            También puedes contactar con la administración <a href="{$contact}">aquí</a>
        </p>
        EOD;
        return $html;
    }
}
