<?php

class Database {
    private static $_instance = null;
    private $_pdo;
    private $_query;
    private $_error = false;
    private $_results;
    private $_count = 0;

    private function __construct()
    {
        try
        {
            $DB_DSN = "mysql:host=".Config::get("mysql/host").";dbname=".Config::get("mysql/db_name").";charset=utf8";
            $DB_PASS = Config::get('mysql/password');
            $DB_USER = Config::get('mysql/username');
            $DB_OPTIONS = Config::get('mysql/db_options');
            $this->_pdo = new PDO($DB_DSN, $DB_USER, $DB_PASS, $DB_OPTIONS);
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }

    public function query($sql, $value = array())
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql))
        {
            $x = 1;
            if (count($value))
            {
                foreach ($value as $param)
                {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute())
            {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            }
            else
            {
                $this->_error = true;
            }
        }
        return $this;
    }

    public function queryNormal($sql, $value = array())
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql))
        {
            $x = 1;
            if (count($value))
            {
                foreach ($value as $param)
                {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if (!$this->_query->execute())
            {
                $this->_error = true;
            }
        }
        return $this;
    }

    public function action($action, $table, $where = array())
    {
        if (count($where) === 3)
        {
            $operators = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if (in_array($operator, $operators))
            {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                

                if (!$this->query($sql, array($value))->error())
                {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where)
    {
        $this->_error = false;
        if (count($where) === 3)
        {
            $operators = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = array($where[2]);
        }
        if (in_array($operator, $operators))
        {
            $sql = "SELECT * FROM {$table} WHERE {$field} {$operator} ?";

            if ($this->_query = $this->_pdo->prepare($sql))
            {
                $x = 1;
                if (count($value))
                {
                    foreach ($value as $param)
                    {
                        $this->_query->bindValue($x, $param);
                        $x++;
                    }
                }
                if ($this->_query->execute())
                {
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    $this->_count = $this->_query->rowCount();
                }
                else
                {
                    $this->_error = true;
                }
            }
        }
        return $this;
    }

    public function resetAutoIncrement($table, $id, $pk = '')
    {
        $this->_pdo->query("ALTER TABLE {$table} DROP {$id}");
        $this->_pdo->query("ALTER TABLE {$table} ADD {$id} int(11) NOT NULL AUTO_INCREMENT {$pk}");
        return $this;
    }

    public function delete($table, $where)
    {
        $this->_error = false;
        if (count($where) === 3)
        {
            $operators = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = array($where[2]);
        }
        if (in_array($operator, $operators))
        {
            $sql = "DELETE FROM {$table} WHERE {$field} {$operator} ?";

            if ($this->_query = $this->_pdo->prepare($sql))
            {
                $x = 1;
                if (count($value))
                {
                    foreach ($value as $param)
                    {
                        $this->_query->bindValue($x, $param);
                        $x++;
                    }
                }
                if (!$this->_query->execute())
                {
                    $this->_error = true;
                }
            }
        }
        return $this;
    }

	public function insert($table, $fields = array())
	{
		if (count($fields));
		{
			$keys = array_keys($fields);
			$values = '';
			$x = 1;

			foreach($fields as $field)
			{
				$values .= '?';
				if ($x < count($fields))
				{
					$values .= ', ';
				}
				$x++;
			}

            $columns = implode('`, `', $keys);
			$sql = "INSERT INTO {$table} (`{$columns}`) VALUES ({$values})";
		
            if ($this->_query = $this->_pdo->prepare($sql))
            {
                $x = 1;
                if (count($fields))
                {
                    foreach ($fields as $param)
                    {
                        $this->_query->bindValue($x, $param);
                        $x++;
                    }
                }
                if (!$this->_query->execute())
                {
                    $this->_error = true;
                }
            }
		}
		return false;
	}

	public function update($table, $location, $id, $fields = array())
	{
		$set = '';
		$x = 1;

		foreach ($fields as $name => $value)
		{
			$set .= "{$name} = ?";
			if ($x < count($fields))
			{
				$set .= ', ';
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE {$location} = {$id}";
		
        if ($this->_query = $this->_pdo->prepare($sql))
        {
            $x = 1;
            if (count($fields))
            {
                foreach ($fields as $param)
                {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if (!$this->_query->execute())
            {
                $this->_error = true;
            }
        }
		return false;
	}

	public function results() {return $this->_results; }

	public function first()	{return $this->results()[0]; }

    public function error() {return $this->_error; }

    public function getCount() {return $this->_count; }
}
