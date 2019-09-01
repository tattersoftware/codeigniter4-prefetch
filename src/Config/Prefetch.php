<?php namespace Tatter\Prefetch\Config;

use CodeIgniter\Config\BaseConfig;

class Prefetch extends BaseConfig
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Maximum size (in bytes) of stored data
	public $maxBufferSize = 0;
}
