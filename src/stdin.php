<?php
$stdin = fopen("php://stdin", "r");
echo fgets($stdin);
fclose($stdin);
