<?php namespace Tatter\Prefetch;

use CodeIgniter\Config\Services;

class Model extends \CodeIgniter\Model
{
	// Static instance of our library
	protected static $prefetch;
	
	// Call CI model constructor then load Prefetch
	public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
	{
        parent::setUp($db, $validation);
		
		if (is_null(self::$prefetch))
		{
			self::$prefetch = Services::prefetch();
		}
	}
}
