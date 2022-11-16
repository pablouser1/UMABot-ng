<?php
namespace App\Helpers;

class Challenge {
    /**
     * Twitter WebHook challenge to register
     */
    static public function register(string $crc_token): string {
        $consumer_secret = Misc::env('CONSUMER_SECRET', '');
        $digest = hash_hmac("sha256", $crc_token, $consumer_secret, true);
        $response = ["response_token" => "sha256=" . base64_encode($digest)];
        return json_encode($response);
    }
    
    /**
     * Verify that request is actually sent by Twitter
     */
    static public function valid(string $signature): bool {
        $digest = hash_hmac("sha256", file_get_contents('php://input'), Misc::env('CONSUMER_SECRET', ''), true);
        $signature2 = "sha256=" . base64_encode($digest);
        return hash_equals($signature2, $signature);
    }
}