# pai

The easy input wrapper for PHP.

> **Abandoned:** Use the `stdin` class of [Asyncore](https://github.com/hell-sh/Asyncore), instead.

## Why?

- Asynchronous input on Windows is only possible using workarounds. [This is a known issue but no one is interested in solving it.](https://bugs.php.net/bug.php?id=34972)
- STDIN is entirely broken on Windows [as of 7.4.](https://stackoverflow.com/questions/59092779/has-php-7-4-broken-fgetsstdin)

## When?

Whenever you need asynchronous or synchronous input of lines to work on all operating systems. If you need character-by-character input, I'm afraid your script will not work on Windows, regardless of what you do.

## Installation

pai has no prerequisites, you can simply `composer require hell-sh/pai` or copy the src folder and require the pai.php in there.

All functions on `hellsh\pai` are [documented here.](https://hell-sh.github.io/pai/classhellsh_1_1pai.html)
