<?php

class ModelTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testFindStoresData()
	{
		$model = new \CIModuleTests\Support\Models\FactoryModel();

		$factories = $model->findAll();
		$this->assertCount(3, $factories);
		
		$store = $this->getPrivateProperty($this->prefetch, 'store');
		dd($store);

		$this->assertCount(3, $store['factories']);
		

	}
}
