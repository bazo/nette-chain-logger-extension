<?php

namespace Bazo\ChainLogger\DI;


use Bazo\ChainLogger\ChainLogger;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class ChainLoggerExtension extends \Nette\DI\CompilerExtension
{

	const TAG_SUBSCRIBER = 'subscriber';

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('logger'))
				->setClass(ChainLogger::class)
				->setInject(FALSE);
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$logger = $builder->getDefinition($this->prefix('logger'));
		foreach (array_keys($builder->findByTag('logger')) as $loggerName) {
			$logger->addSetup('addLogger', ['@' . $loggerName]);
		}
	}


	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$initialize = $class->methods['initialize'];

		$initialize->addBody('\Tracy\Debugger::setLogger($this->getService(?));', [$this->prefix('logger')]);
	}


}
