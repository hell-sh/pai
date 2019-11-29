<?php
require __DIR__."/../src/pai.php";
use hellsh\pai;
pai::init();
while(!pai::hasLine())
{
	echo "Awaiting input...\n";
	sleep(1);
}
echo "Got input: ".pai::getLine()."\n";
