<?php

class Validate {
    private $_passed = false;
    private $_errors = array();
    private $_db = null;

    public function __construct()
    {
        $this->_db = Database::getInstance();
	}

    public function check($source = array(), $items = array())
    {
		foreach ($items as $item => $rules)
        {
            foreach ($rules as $rule => $rule_value)
            {
				$value = $source[$item];
				$item = escape($item);
				if ($rule === 'required' && empty($value))
				{
					$this->addError("{$item} is required");
				}
				else if (!empty($value))
				{
					switch($rule)
					{
						case 'min':
							if (strlen($value) < $rule_value)
							{
								$this->addError("{$item} must be a minimum of {$rule_value} characters");
							}
						break;
						case 'max':
							if (strlen($value) > $rule_value)
							{
								$this->addError("{$item} must be a maximum of {$rule_value} characters");
							}
						break;
						case 'strength':
							if ($rule === 'strength')
							{
								$uppercase = preg_match('@[A-Z]@', $value);
								$lowercase = preg_match('@[a-z]@', $value);
								$number = preg_match('@[0-9]@', $value);

								if (!$uppercase || !$lowercase || !$number)
								{
									$this->addError("{$item} must contain a number, Upper, & Lower case characters!");
								}
							}
						break;
						case 'matches':
							if ($value != $source[$rule_value])
							{
								$this->addError("{$item} must match {$rule_value}");
							}
						break;
						case 'type':
							if ($rule_value === 'email')
							{
								if (!filter_var($source[$rule_value], FILTER_VALIDATE_EMAIL))
								{
									$this->addError("{$rule_value} entered not a valid {$rule_value}");
								}
							}
						break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->getCount())
							{
								$this->addError("{$item} already exists");
							}
						break;
					}
				}
			}
		}
		if(empty($this->_errors))
		{
			$this->_passed = true;
		}
		return $this;
	}

    private function addError($error)
    {
		$this->_errors[] = $error;
	}
	
	public function insertError($error)
    {
		$this->_errors[] = $error;
	}

	public function errors()
	{
		return $this->_errors;
	}

	public function passed()
	{
		return $this->_passed;
	}

	public function setPassed($value)
	{
		$this->_passed = $value;
	}
}