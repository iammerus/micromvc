<?php
	namespace MicroPos\Core\Helpers;


	use MicroPos\Core\Database;
	use MicroPos\Core\Exception\DatabaseConnectionException;

	class DB
	{

		/**
		 * Application's database connection instance
		 * @var \PDO
		 */
		private static $connection = null;

		public function __construct()
		{
			$this->connect();
		}

		/**
		 * Check if a specified table exists in the application's database
		 * @param string $table
		 * @return boolean
		 */
		protected static function hasTable($table)
		{
			try {
				static::selectRand( $table );

				return true;
			} catch (\PDOException $ex) {
				return false;
			}
		}

		/**
		 * Truncates the specified database table
		 * @param string $table Table to truncate
		 * @throws \PDOException If database user does not have sufficient
		 * privileges or if the specified table does not exists
		 */
		public static function truncate($table)
		{
			static::$connection->query( "TRUNCATE TABLE `{$table}`" );
		}

		/**
		 * Counts the rows in the specified table
		 * @param string $table The table to count from
		 * @return int
		 * @throws \PDOException If database user does not have sufficient
		 * privileges or if the specified table does not exists
		 */
		public static function countAll($table)
		{
			return (int)static::$connection
				->query( "SELECT COUNT(*) AS totalCount FROM `{$table}`" )
				->fetchObject()
				->totalCount;
		}

		/**
		 * Counts the number of rows in a table that meet the specified conditions
		 * @param int   $table The table to count from
		 * @param array $conditions
		 * @return int|null
		 * @throws \InvalidArgumentException
		 */
		public static function count($table , array $conditions = [])
		{
			if (empty($table) || is_null( $conditions )) {
				throw new \InvalidArgumentException;
			}

			if (empty($conditions)) {
				return static::countAll( $table );
			}

			$conditions = static::conditionString( $conditions );

			return (int)static::$connection
				->query( "SELECT COUNT(*) AS totalCount FROM {$table} {$conditions}" )
				->fetchObject()
				->totalCount;
		}


		/**
		 * Format the given conditions into an sql string
		 * @param array $conditions
		 * @return string
		 */
		protected static function conditionString(array $conditions)
		{
			$queryString = "";

			$index = 0;
			foreach ($conditions as $condition) {
				$index++;

				if ($index == 1) {
					$queryString .= " WHERE `{$condition[0]}` {$condition[1]} '$condition[2]'";
				}
				else {
					$queryString .= " AND `{$condition[0]}` {$condition[1]} '$condition[2]'";
				}
			}

			return $queryString;
		}

		/**
		 * Gets random records from the specified database table
		 * @param string $table The table to get data from
		 * @param int    $limit
		 * @throws \PDOException If the entered table is not found
		 * @return array
		 */
		public static function selectRand($table , $limit = 1)
		{
			$sql = "SELECT * FROM {$table} ORDER BY RAND() LIMIT {$limit}";

			return static::$connection->query( $sql )->fetchAll();
		}

		/**
		 * Gets all records from the specified database table
		 * @param string $table The table to get data from
		 * @throws \PDOException If the entered table is not found
		 * @return array
		 */
		public static function selectAll($table)
		{
			$sql = "SELECT * FROM $table";

			return static::$connection->query( $sql )->fetchAll();
		}

		/**
		 * Get records from the specified table that meets the specified conditions
		 *
		 * @param       $table
		 * @param array $columns
		 * @param       $column
		 * @param       $operator
		 * @param       $value
		 * @param null  $limit
		 * @return array
		 */
		public static function select($table , array $columns , $column , $operator , $value , $limit = null)
		{
			if (!is_null( $columns ) && !empty($columns) && is_array( $columns )) {
				$columns = static::columnsString( $columns );
			}
			else {
				$columns = "*";
			}

			$sql = "SELECT {$columns} FROM {$table} WHERE {$column} {$operator} '{$value}'";

			if (!is_null( $limit )) {
				$sql .= " LIMIT {$limit}";
			}

			return static::$connection->query( $sql )->fetchAll();
		}

		/**
		 * Create a new database connection
		 */
		protected function connect()
		{
			static::$connection = Database::getPDOInstance();

			if (is_null( static::$connection )) {
				throw new DatabaseConnectionException( "Database connection failed" );
			}
		}

		/**
		 * Get the string representation of the specified columns
		 * @param array $columns
		 * @return string
		 */
		protected static function columnsString(array $columns)
		{
			foreach ($columns as $key => $column) {
				$columns[$key] = "`{$column}`";
			}

			return implode( ',' , $columns );
		}

		/**
		 * Concanates the given values into a single value
		 * @param array $values multidimensional array
		 * @return string
		 */
		protected static function valueString(array $values)
		{
			if (empty($values)) {
				return null;
			}

			$valString = "";

			$valCount = count( Arr::first( $values ) );

			$count = count( $values );

			$vals = "(%s)";


			for ($i = 0; $i < $count; $i++) {
				$val = "";

				for ($x = 0; $x < $valCount; $x++) {
					if ($x == ($valCount - 1)) {
						$val .= "`{$values[$i][$x]}`";
					}
					else {
						$val .= "`{$values[$i][$x]}`,";
					}
				}

				$val = sprintf( $vals , $val );

				if ($i == ($count - 1)) {
					$val .= ";";
				}
				else {
					$val .= ",";
				}

				$valString .= $val;
			}

			return $valString;
		}

		/**
		 * Insert a row into the specified table with the specified columns and
		 * data
		 * @param string $table
		 * @param array  $columns
		 * @param array  $data
		 * @return bool
		 */
		public static function insert($table , array $columns , array $data)
		{
			$sql = "INSERT INTO `{$table}`({static::columnsString($columns)}) VALUES {static::valueString($data)}";

			return static::$connection->query( $sql );
		}


		/**
		 * Run an SQL query on the application database
		 *
		 * @param string $query SQL query to run
		 * @return \PDOStatement|bool
		 */
		public static function query($query)
		{
			return static::$connection->query($query);
		}

		/**
		 * @param string $table
		 * @param string $column
		 * @param string $value
		 * @return bool
		 */
		public static function rowExists($table , $column , $value)
		{
			$sql = "SELECT * FROM {$table} WHERE {$column} = '$value'";

			$data = static::$connection->query( $sql );

			if (is_null( $data )) {
				return false;
			}

			if ($data->rowCount() <= 0) {
				return false;
			}

			return true;
		}

		public static function error()
		{
			return static::$connection->errorInfo();
		}

	}
