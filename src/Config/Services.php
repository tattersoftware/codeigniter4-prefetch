<?php namespace Tatter\Prefetch\Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function prefetch($config = null, bool $getShared = true)
    {
		if ($getShared)
			return static::getSharedInstance('prefetch', $config);

		// If no config was injected then load one
		$config = $config ?? config('Prefetch');
		return new \Tatter\Prefetch\Prefetch($config);
	}
}
