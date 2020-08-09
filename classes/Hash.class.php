<?php

class Hash {
    public static function make($string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function verify($string, $hashed)
    {
        if(password_verify($string, $hashed))
        {
            return true;
        }
        return false;
    }

    public static function unique()
    {
        return hash('sha256', uniqid());
    }
    
}

?>