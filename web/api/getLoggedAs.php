<?php
ini_set('display_errors', '1');
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/user.php";


header('Content-Type: text/plain');
print_r(User::getUsername());