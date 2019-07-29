<?php
namespace hellsh;
abstract class pai
{
	private static $init = false;
	private static $stdin;
	private static $proc;
	private static $pipes;

	/**
	 * Used internally to initialize. You don't have to call this.
	 */
	static function init()
	{
		if(self::$init)
		{
			echo "You don't need to call pai::init.\n";
			return;
		}
		self::$init = true;
		if(self::isWindows())
		{
			self::openProcess();
		}
		else
		{
			self::$stdin = fopen("php://stdin", "r");
			stream_set_blocking(self::$stdin, false);
		}
	}

	/**
	 * Returns true if the code is running on a Windows machine.
	 *
	 * @return boolean
	 */
	static function isWindows()
	{
		return defined("PHP_WINDOWS_VERSION_MAJOR");
	}

	private static function openProcess()
	{
		self::$proc = proc_open("php \"".__DIR__."\\stdin.php\"", [
			0 => [
				"file",
				"php://stdin",
				"r"
			],
			1 => [
				"pipe",
				"w"
			],
			2 => [
				"pipe",
				"w"
			]
		], self::$pipes);
		if(!is_resource(self::$proc))
		{
			die("[pai] Failed to start PHP sub-process. Has PHP been added to PATH?\n");
		}
	}

	/**
	 * Returns true if the user has submitted a line of text.
	 *
	 * @return boolean
	 */
	static function hasLine()
	{
		if(self::isWindows())
		{
			return !proc_get_status(self::$proc)["running"];
		}
		$read = [self::$stdin];
		$null = [];
		return stream_select($read, $null, $null, 0) === 1;
	}

	/**
	 * Returns the line of text the user has submitted.
	 * Please make sure hasLine() == true before calling this to avoid issues.
	 *
	 * @return string
	 */
	static function getLine()
	{
		if(self::isWindows())
		{
			$ret = trim(stream_get_contents(self::$pipes[1]));
			self::openProcess();
			return $ret;
		}
		return trim(fgets(self::$stdin));
	}
}

pai::init();
