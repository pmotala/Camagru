<?php

class User {
    private $_db;
    private $_data;
    private $_sessionName;
    private $_cookieName;
    private $_isLoggedIn = false;

    public function __construct($user = null)
    {
        $this->_db = Database::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user)
        {
            if (Session::exists($this->_sessionName))
            {
                $user = Session::get($this->_sessionName);
                if ($this->find($user))
                {
                    $this->_isLoggedIn = true;
                }
            }
        }
        else
        {
            $this->find($user);
        }
    }

    public function update($fields = array(), $id = null)
    {
        if (!$id && $this->isLoggedIn())
        {
            $id = $this->data()->ID;
        }

        $this->_db->update('users', 'ID', $id, $fields);
    }

    public function create($fields = array())
    {
        $this->_db->insert('users', $fields);
    }

    public function find($user = null)
    {
        if ($user)
        {
            $field = (is_numeric($user)) ? 'ID' : 'USERNAME';
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->getCount())
            {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists())
        {
            Session::put($this->_sessionName, $this->data()->ID);
        }
        else
        {
            $user = $this->find($username);
            if ($user)
            {
                if(Hash::verify($password, $this->data()->PASSWORD))
                {
                    Session::put($this->_sessionName, $this->data()->ID);

                    if($remember)
                    {
                        $hashCheck = $this->_db->get('user_session', array('USERID', '=', $this->data()->ID));

                        if (!$hashCheck->getCount())
                        {
                            $hash = Hash::unique();
                            $this->_db->insert('user_session', array(
                                'USERID' => $this->data()->ID,
                                'HASH' => $hash
                            ));
                        }
                        else
                        {
                            $hash = $hashCheck->first()->HASH;
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    
                    return true;
                }
            }
        }
        return false;
    }

    public function logout()
    {
        $this->_db->delete('user_session', array('USERID', '=', $this->data()->ID));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}