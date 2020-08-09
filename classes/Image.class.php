<?php

class Image {
	private $_db;
	private $_imageData;
	private $_imageType;
	private $_imageTmpName;
	private $_data;
	private $_errors;
	private $_count;

	public function __construct($source = null)
	{
		$this->_db = Database::getInstance();

		if ($source)
		{
			$this->_imageType = escape($source['type']);
			$this->_imageTmpName = escape($source['tmp_name']);
			$this->_imageData = base64_encode(file_get_contents($this->_imageTmpName));

			if (!substr($this->_imageType, 0, 5) === 'image')
			{
				$this->addError("File is Not an Image");
			}
		}
	}

	public function upload($fields = array())
	{
		$this->_db->insert('uploads', $fields);
	}

	public function retrieve($id = null, $field = 'ID')
	{
		if ($id)
		{
			if ($field === 'ID')
			{
				$data = $this->_db->get('uploads', array($field, '=', $id));
				if ($data->getCount())
				{
					$this->_data = $data->first();
					return true;
				}
			}
			else if ($field === 'COMM_ID')
			{
				$data = $this->_db->get('uploads', array($field, '=', $id));
				if ($data->getCount())
				{
					$this->_data = $data->first();
					return true;
				}
			}
			else if ($field === 'USERID')
			{
				$data = $this->_db->get('uploads', array($field, '=', $id));
				if ($data->getCount())
				{
					$this->_data = $data;
					$this->_count = $this->_data->getCount();
					return true;
				}
			}
			else if ($field === 'ALL')
			{
				$data = $this->_db->get('uploads', array('ID', '>=', $id));
				if ($data->getCount())
				{
					$this->_data = $data;
					$this->_count = $this->_data->getCount();
					return true;
				}
			}
		}
		$this->addError("Image not found.");
		return false;
	}

	public static function checkFile($allowed = array(), $source = null)
	{
		if ($source)
		{
			$fileName = $source['name'];
			$fileError = $source['error'];
			$fileExt = explode(".", $fileName);
			$fileActualExt = strtolower(end($fileExt));

			if (in_array($fileActualExt, $allowed))
			{
				if ($fileError === 0)
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		return false;
	}

	public function update($fields = array(), $id = null)
    {
        $this->_db->update('uploads', 'USERID', $id, $fields);
	}

	public function data() {return $this->_data; }

	public function errors() {return $this->_errors; }

	public function getCount() {return $this->_count; }

	private function addError($error) {$this->_error[] = $error; }

	public function imageData() {return $this->_imageData; }

	public function imageType() {return $this->_imageType; }

}