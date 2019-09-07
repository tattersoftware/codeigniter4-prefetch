<?php

class ModelTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testFindStoresEveryItem()
	{
		$model = new \CIModuleTests\Support\Models\FactoryModel();

		$factories = $model->findAll();
		$this->assertCount(3, $factories);
		
		$store = $this->getPrivateProperty($this->prefetch, 'store');
		$this->assertCount(3, $store['factories']);
	}

	public function testFindStoresSpecificItem()
	{
		$model = new \CIModuleTests\Support\Models\WorkerModel();
		$worker = $model->first();
		
		$store = $this->getPrivateProperty($this->prefetch, 'store');
		$this->assertContains($worker, $store['workers']);
	}

	public function testStorePersistsAfterDirectDelete()
	{
		$model = new \CIModuleTests\Support\Models\WorkerModel();
		$worker = $model->first();

		$this->db->table('workers')->delete(['id' => $worker->id]);
		
		$test = $model->find($worker->id);

		$this->assertEquals($test, $worker);
	}
}
