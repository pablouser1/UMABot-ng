<?php

use App\Twitter;

require '../vendor/autoload.php';
require '../bootstrap.php';

$twitter = new Twitter;
$client = $twitter->getClient();

if ($argc < 3) {
    die("Necesitas mandar una URL y el env name!");
}

$url = $argv[1];
$env = $argv[2];
$content = $client->post("account_activity/all/{$env}/webhooks", ["url" => $url . '/webhook/twitter']);
echo json_encode($content);
