<?php
// Manually verify user

use App\Items\User;

require '../vendor/autoload.php';
require '../bootstrap.php';

if ($argc < 3) {
    die("Necesitas mandar el id del usuario y su NIU!");
}

$user = new User();

$user_id = $argv[1];
$niu = $argv[2];

$user->add($user_id, $niu);
