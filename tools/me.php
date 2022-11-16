<?php

use App\Twitter;

require '../vendor/autoload.php';
require '../bootstrap.php';

$twitter = new Twitter;

$content = $twitter->me();
echo json_encode($content);
