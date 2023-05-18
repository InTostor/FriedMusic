<?php
header('WWW-Authenticate: Basic realm="My Realm"');
phpinfo();
echo $_SERVER['PHP_AUTH_USER'];echo"<br>";
echo $_SERVER['PHP_AUTH_PW'];

echo var_dump(isset($_SERVER['PHP_AUTH_USER']));