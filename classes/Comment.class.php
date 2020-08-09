<?php

class Comment {
	private $_db;
	private $_data;
	private $_count;
	
	public function __construct()
	{
		$this->_db = Database:: getInstance();
	}

	public function create($fields = array())
	{
		$this->_db->insert('comments', $fields);
	}

	public function retrieve($id = null, $field = "ID")
	{
		if ($field === 'ID')
		{
			$data = $this->_db->get('comments', array($field, '=', $id));
			if ($data->getCount())
			{
				$this->_data = $data->first();
				return true;
			}
		}
		else if ($field === 'COMM_ID')
		{
			$data = $this->_db->get('comments', array($field, '=', $id));
			if ($data->getCount())
			{
				$this->_data = $data;
				$this->_count = $this->_data->getCount();
				return true;
			}
		}
		return false;
	}

	public function update($fields = array(), $id = null)
    {
        $this->_db->update('comments', 'USERID', $id, $fields);
	}

	public function exists()
	{
		return (!empty($this->_data)) ? true : false;
	}

	public function data()
	{
		return $this->_data;
	}
}

?>