<?php
use App\Twitter;

require '../vendor/autoload.php';
require '../bootstrap.php';

$twitter = new Twitter;
$client = $twitter->getClient();

if ($argc < 2) {
    die("Necesitas mandar el env name!");
}


$env = $argv[1];

$content = $client->post("account_activity/all/{$env}/subscriptions");  
echo json_encode($content);
