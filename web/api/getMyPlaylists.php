<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/user.php";
require_once "$root/lib/getUserPlaylists.php";

$uname = User::getUsername();
header('Content-Type: text/plain');


echo (implode("\n",User::getPlaylists($uname)));