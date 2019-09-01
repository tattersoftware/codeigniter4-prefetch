<?php namespace Tatter\Prefetch\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class PrefetchException extends \RuntimeException implements ExceptionInterface
{
	
	public static function forInvalidFormat($class)
	{
		return new static(lang('Prefetch.invalidFormat', [$class]));
	}
}
