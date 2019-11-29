<?php /** @noinspection PhpUnused */
namespace hellsh;
use BadMethodCallException;
use RuntimeException;
abstract class pai
{
	private static $initialized = false;
	private static $proc;
	private static $pipes;

	/**
	 * Initializes pai's input handling.
	 * After this, STDIN is in pai's hands, and there's no way out.
	 *
	 * @throws BadMethodCallException if pai is already initialized.
	 */
	static function init()
	{
		if(self::$initialized)
		{
			throw new BadMethodCallException("pai was already initialized");
		}
		self::$initialized = true;
		if(self::isWindows())
		{
			self::openProcess();
		}
		else
		{
			stream_set_blocking(STDIN, false);
		}
	}

	/**
	 * Returns true if pai is already initialized.
	 *
	 * @since 2.1
	 * @return bool
	 */
	static function isInitialized()
	{
		return self::$initialized;
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
		self::$proc = proc_open("SET /P pai_input= & SET pai_input", [
			0 => STDIN,
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
			throw new RuntimeException("Failed to start Windows input process");
		}
	}

	/**
	 * Returns true if the user has submitted a line of text.
	 *
	 * @throws BadMethodCallException if pai is not initialized
	 * @see pai::init()
	 * @return boolean
	 */
	static function hasLine()
	{
		if(!self::$initialized)
		{
			throw new BadMethodCallException("pai is not initialized");
		}
		if(self::isWindows())
		{
			return !proc_get_status(self::$proc)["running"];
		}
		else
		{
			$read = [STDIN];
			$null = [];
			return stream_select($read, $null, $null, 0) === 1;
		}
	}

	/**
	 * Returns the line of text the user has submitted or null if they haven't.
	 *
	 * @throws BadMethodCallException if pai is not initialized
	 * @see pai::init()
	 * @return string|null
	 */
	static function getLine()
	{
		if(!self::hasLine())
		{
			return null;
		}
		if(self::isWindows())
		{
			if(!self::hasLine())
			{
				return null;
			}
			$res = trim(substr(stream_get_contents(self::$pipes[1]), 10));
			self::openProcess();
			return $res;
		}
		return trim(fgets(STDIN));
	}

	/**
	 * Waits until the user has submitted a line and then returns it.
	 *
	 * @since 2.1
	 * @throws BadMethodCallException if pai is not initialized
	 * @see pai::init()
	 * @return string
	 */
	static function awaitLine()
	{
		if(!self::$initialized)
		{
			throw new BadMethodCallException("pai is not initialized");
		}
		while(!pai::hasLine())
		{
			usleep(100000);
		}
		return pai::getLine();
	}
}
