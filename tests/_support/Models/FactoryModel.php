<?php namespace CIModuleTests\Support\Models;

use Tatter\Prefetch\Model;

class FactoryModel extends Model
{
	protected $table      = 'factories';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $useSoftDeletes = true;

	protected $allowedFields = ['name', 'uid', 'class', 'icon', 'summary'];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;
}
