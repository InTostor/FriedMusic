<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/Locale.php";
require_once "$root/lib/user.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/Library.php";
require_once "$root/lib/apiCooldown.php";




fout(shell_exec("git -h 2>&1"));