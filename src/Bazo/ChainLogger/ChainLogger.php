<?php

namespace Bazo\ChainLogger;


use Tracy\ILogger;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class ChainLogger implements ILogger
{

	/** @var array */
	private $loggers = [];

	public function addLogger($logger)
	{
		$this->loggers[spl_object_hash($logger)] = $logger;
	}


	public function log($message, $priority = self::INFO)
	{
		foreach ($this->loggers as $logger) {
			$logger->log($message, $priority);
		}
	}


}
