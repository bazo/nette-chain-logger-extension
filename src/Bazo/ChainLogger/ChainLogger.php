<?php

namespace Bazo\ChainLogger;


use Tracy\Logger;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class ChainLogger extends Logger
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
