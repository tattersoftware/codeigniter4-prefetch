<?php

class ExampleDatabaseTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testDatabaseSimple()
	{
		$model = new \CIModuleTests\Support\Models\FactoryModel();

		$objects = $model->findAll();
		$this->assertCount(3, $objects);

		$objects = $model->withDeleted()->findAll();
		$this->assertCount(4, $objects);
	}
}
