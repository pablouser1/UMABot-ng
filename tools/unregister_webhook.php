<?php

use App\Twitter;

require '../vendor/autoload.php';
require '../bootstrap.php';

$twitter = new Twitter;
$client = $twitter->getClient();

if ($argc < 3) {
    die("Necesitas mandar la ID del entorno y la del webhook!");
}

$env = $argv[1];
$webhook_id = $argv[2];
$content = $client->delete("account_activity/all/{$env}/webhooks/{$webhook_id}");
echo $content;
