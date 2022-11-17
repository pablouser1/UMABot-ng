<?php
// Manually verify user

use App\Db;

require '../vendor/autoload.php';
require '../bootstrap.php';

$db = new Db;

if ($argc < 3) {
    die("Necesitas mandar el id del usuario y su NIU!");
}

$user_id = $argv[1];
$niu = $argv[2];

$db->addUser($user_id, $niu);
