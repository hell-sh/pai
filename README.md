# pai — php asynchronous input

The elegant solution to asynchronous input in PHP.

PHP's Windows port has [a bug](https://bugs.php.net/bug.php?id=34972) that makes asynchronous input _impossible_, but Pai provides a simple API for asynchronous user input handling, including a workaround for Windows machines.

Note that pai can only read line-by-line, not character-by-character, which I'm afraid is actually impossible on Windows.

## Installation

Pai has no prerequisites, you can simply `composer require hell-sh/pai` or copy the src folder and require the pai.php in there.

## An Example

```PHP
<?php
require "vendor/autoload.php";
use hellsh\pai;
while(true)
{
    if(pai::hasLine())
    {
        echo pai::getLine()."\n";
    }
}
```

Of course, this example is synchronous, but don't let that impede your creativity! 
