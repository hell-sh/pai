<?php
require __DIR__."/../src/pai.php";
use hellsh\pai;
pai::init();
echo "Got input: ".pai::awaitLine()."\n";
