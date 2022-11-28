<?php
namespace App;

use App\Constants\Messages;
use App\Helpers\Misc;
use PHPMailer\PHPMailer\PHPMailer;

class Mail {
    private PHPMailer $client;

    function __construct() {
        $host = Misc::env('MAIL_HOST', '');
        $port = Misc::env('MAIL_PORT', 465);
        $username = Misc::env('MAIL_USERNAME', '');
        $password = Misc::env('MAIL_PASSWORD', '');
        $encryption = Misc::env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_SMTPS);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $encryption;
        $mail->Port = $port;
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $this->client = $mail;
    }


    public function sendCode(string $destionation, string $pin): bool {
        $this->client->setFrom($this->client->Username, 'UMABot');
        $this->client->addAddress($destionation);
        $this->client->Subject = Messages::EMAIL_SUBJECT;
        $this->client->Body = $this->__html($pin);
        $this->client->AltBody = $this->__plain($pin);
        $success = $this->client->send();
        return $success;
    }

    private function __plain(string $pin): string {
        $verify = Misc::url('/verify');
        $contact = Misc::contact();
        $plain = sprintf(Messages::EMAIL_PLAIN, $pin, $verify, $contact);
        return $plain;
    }

    private function __html(string $pin): string {
        $verify = Misc::url('/verify');
        $contact = Misc::contact();
        $html = sprintf(Messages::EMAIL_HTML, $pin, $verify, $contact);
        return $html;
    }
}
