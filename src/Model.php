<?php namespace Tatter\Prefetch;

use CodeIgniter\Config\Services;

class Model extends \CodeIgniter\Model
{
	// Static instance of our library
	protected static $prefetch;
	
	// Tracker for if all items have been fetched
	protected $haveAll = false;
	
	// Tracker for if deleted items have been fetched
	protected $haveDeleted;
	
	// Call CI model constructor then load Prefetch
	public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
	{
        parent::__construct($db, $validation);
		
		if (is_null(self::$prefetch))
		{
			self::$prefetch = Services::prefetch();
		}
		
		// If the model doesn't use soft deletes then we already have deleted items
		$this->haveDeleted = ! $this->useSoftDeletes;
		
		// Add the collectors to each event
		foreach (['Find', 'Insert', 'Update', 'Delete'] as $name)
		{
			$event = 'after' . $name;
			$this->{$event}[] = 'prefetchAfter' . $name;
		}
	}

	//--------------------------------------------------------------------
	// OVERLOADED FINDERS
	//--------------------------------------------------------------------

	/**
	 * Fetches the row of database from $this->table with a primary key
	 * matching $id.
	 *
	 * @param mixed|array|null    $id One primary key or an array of primary keys
	 *
	 * @return array|object|null  The resulting row of data, or null.
	 */
	public function find($id = null)
	{
		if (is_array($id))
		{
			$singleton = false;
		}
		// Force a single primary key into an array
		elseif (is_numeric($id) || is_string($id))
		{
			$singleton = true;
			$id = [$id];
		}
		// If no items were requested this is a "SELECT...WHERE" to pass on
		else
		{
			return parent::find($id);
		}
		
		// Check the store for requested items
		$items = [];
		$remainder = self::$prefetch->fetch($this->table, $id, $items);
			
		// If the store had everything then we are done
		if (empty($remainder))
		{
			return $singleton ? reset($items) : $items;
		}
		
		// Pass through the remaining items to find
		return parent::find($remainder);		
	}

	//--------------------------------------------------------------------

	/**
	 * Fetches the column of database from $this->table
	 *
	 * @param string $columnName
	 *
	 * @return array|null   The resulting row of data, or null if no data found.
	 * @throws \CodeIgniter\Database\Exceptions\DataException
	 *
	public function findColumn(string $columnName)
	{
		if (strpos($columnName, ',') !== false)
		{
			throw DataException::forFindColumnHaveMultipleColumns();
		}

		$resultSet = $this->select($columnName)
						  ->asArray()
						  ->find();

		return (! empty($resultSet)) ? array_column($resultSet, $columnName) : null;
	}

	//--------------------------------------------------------------------

	/**
	 * Works with the current Query Builder instance to return
	 * all results, while optionally limiting them.
	 *
	 * @param integer $limit
	 * @param integer $offset
	 *
	 * @return array|null
	 *
	public function findAll(int $limit = 0, int $offset = 0)
	{
		$builder = $this->builder();

		if ($this->tempUseSoftDeletes === true)
		{
			$builder->where($this->table . '.' . $this->deletedField, null);
		}

		$row = $builder->limit($limit, $offset)
				->get();

		$row = $row->getResult($this->tempReturnType);

		$row = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

		$this->tempReturnType     = $this->returnType;
		$this->tempUseSoftDeletes = $this->useSoftDeletes;

		return $row['data'];
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the first row of the result set. Will take any previous
	 * Query Builder calls into account when determining the result set.
	 *
	 * @return array|object|null
	 *
	public function first()
	{
		$builder = $this->builder();

		if ($this->tempUseSoftDeletes === true)
		{
			$builder->where($this->table . '.' . $this->deletedField, null);
		}

		// Some databases, like PostgreSQL, need order
		// information to consistently return correct results.
		if (empty($builder->QBOrderBy) && ! empty($this->primaryKey))
		{
			$builder->orderBy($this->table . '.' . $this->primaryKey, 'asc');
		}

		$row = $builder->limit(1, 0)
				->get();

		$row = $row->getFirstRow($this->tempReturnType);

		$row = $this->trigger('afterFind', ['data' => $row]);

		$this->tempReturnType = $this->returnType;

		return $row['data'];
	}
*/

	//--------------------------------------------------------------------
	// EVENTS
	//--------------------------------------------------------------------

	/**
	 * Intercepts items fresh from the database and adds them to the store
	 *
	 * @param array $data  An array whose elements vary by find method:
	 *		find()
	 *			id = the primary key(s) of the row(s) being searched for.
	 *			data = The resulting row(s) of data, or null if no result found.
	 *
	 *		findAll()
	 *			data = the resulting rows of data, or null if no result found.
	 *			limit = the number of rows to find.
	 *			offset = the number of rows to skip during the search.
	 *
	 *		first()
	 *			data = the resulting row found during the search, or null if none found.
	 *
	 * @return array  The original data array (for other event callbacks)
	 */
	protected function prefetchAfterFind(array $data)
	{
		$items = $data['data'];
		
		// find() - id can be null, id, or [ids]
		if (array_key_exists('id', $data))
		{
			// Force a single primary key into an array
			if (is_numeric($data['id']) || is_string($data['id']))
			{
				$ids = [$data['id']];
				$items = [$items];
			}
			// Already an array
			elseif (is_array($data['id']))
			{
				$ids = $data['id'];
			}
		}
		
		// findAll() - Check for a complete set
		elseif (isset($data['limit']))
		{			
			// WIP - need some way to determine if findAll() was called without a WHERE to know if we have a complete set
			// probably a flag in overloaded findAll() above
		}
		
		// first() - Always a singleton
		else
		{
			$items = [$items];
		}
		
		self::$prefetch->collect($this->table, $this->primaryKey, $items, $ids ?? []);

		unset($items);
		return $data;
	}
}
