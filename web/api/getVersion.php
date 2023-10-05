<?php

header('Content-Type: text/plain');

echo(shell_exec("git rev-parse HEAD 2>&1"));