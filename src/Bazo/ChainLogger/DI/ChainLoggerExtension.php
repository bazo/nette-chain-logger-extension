<?php

namespace Bazo\ChainLogger\DI;

/**
 * Description of ChainLoggerExtension
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class ChainLoggerExtension extends \Nette\DI\CompilerExtension
{
	/** @var Logger */
	private $logger;
	
	private $defaults = [
		'loggers' => [],
	];
	
	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$logger = $containerBuilder->addDefinition($this->prefix('logger'))
						->setClass('Bazo\ChainLogger\ChainLogger');
		
		foreach ($config['loggers'] as $loggerName => $implementation) {
			$this->compiler->parseServices($containerBuilder, [
				'services' => [
					$this->prefix($loggerName) => $implementation,
				],
			]);

			$logger->addSetup('addLogger', [$this->prefix('@' . $loggerName)]);
		}
		
		$containerBuilder instanceof \Nette\DI\ContainerBuilder;
		foreach(array_keys($containerBuilder->findByTag('logger')) as $loggerName) {
			$logger->addSetup('addLogger', ['@' . $loggerName]);
		}
	}

	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$initialize = $class->methods['initialize'];

		$initialize->addBody('\Nette\Diagnostics\Debugger::$logger = $this->getService(?);', [$this->prefix('logger')]);
	}
}

