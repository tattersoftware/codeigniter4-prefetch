<?php namespace Config;

/***
*
* This file contains example values to alter default library behavior.
* Recommended usage:
*	1. Copy the file to app/Config/Prefetch.php
*	2. Change any values
*	3. Remove any lines to fallback to defaults
*
***/

class Prefetch extends \Tatter\Prefetch\Config\Prefetch
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Maximum size (in bytes) of stored data
	public $maxBufferSize = 0;
}
