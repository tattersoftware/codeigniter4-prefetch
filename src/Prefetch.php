<?php namespace Tatter\Prefetch;

use CodeIgniter\Config\BaseConfig;

class Prefetch
{
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Prefetch\Config\Prefetch
	 */
	protected $config;
	
	/**
	 * Warehouse of prefetched items
	 *
	 * @var array
	 */
	protected $_store;
	
	/**
	 * Array error messages assigned on failure
	 *
	 * @var array
	 */
	protected $errors;
	
	// Initiate library
	public function __construct(BaseConfig $config)
	{		
		// Save the configuration
		$this->config = $config;
	}
	
	// Return any error messages
	public function getErrors()
	{
		return $this->errors;
	}
	
	// Set heuristics flag on-the-fly
	public function setHeuristics(bool $bool)
	{
		$this->config->heuristics = $bool;
		return $this;
	}
	
	// Set training flag on-the-fly
	public function setTraining(bool $bool)
	{
		$this->config->training = $bool;
		return $this;
	}
}
