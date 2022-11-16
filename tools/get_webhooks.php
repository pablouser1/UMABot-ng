<?php

use App\Twitter;

require '../vendor/autoload.php';
require '../bootstrap.php';

$twitter = new Twitter;
$client = $twitter->getClient();

$content = $client->get("account_activity/all/webhooks");
echo json_encode($content);
