<?php

class Token {
    public static function generate($name = null)
    {
        $token = md5(uniqid());
        if ($name)
        {
            Session::put($name, $token);
        }
        else
        {
            Session::put(Config::get('session/token_name'), $token);
        }
        return $token;
    }

    public static function check($token, $name = null)
    {
        if ($name)
        {
            $tokenName = $name;
        }
        else
        {
            $tokenName = Config::get('session/token_name');
        }

        if(Session::exists($tokenName) && $token === Session::get($tokenName))
        {
            Session::delete($tokenName);
            return true;
        }

        return false;
    }
}

?>