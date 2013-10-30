<?php

namespace Bazo\ChainLogger;

use \Nette\Diagnostics\Logger as NetteLogger;

/**
 * Description of Logger
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class ChainLogger extends NetteLogger
{
	/** @var NetteLogger[] */
	private $loggers = [];

	public function addLogger(NetteLogger $logger)
	{
		$this->loggers[spl_object_hash($logger)] = $logger;
	}

	public function log($message, $priority = self::INFO)
	{
		foreach($this->loggers as $logger)
		{
			$logger->log($message, $priority);
		}
	}

	public static function register()
	{
		\Nette\Diagnostics\Debugger::$logger = new static();
	}
}

