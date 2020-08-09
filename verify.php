<?php
    require_once 'core/init.php';

    if (Input::exists("GET"))
    {
        $code = Input::get('code');
        $username = Input::get('user');

        $user = new User();
        $user->find($username);

        if ($user->data()->USERNAME === $username)
        {
            if ($user->data()->ACTIVATION_CODE === $code)
            {
                $user->update(array(
                    'ACTIVATED' => 1
                ), $user->data()->ID);
                Session::flash('login', "Account Successfully Confirmed!");
                header("Location: login.php");
            }
            else
            {
                Session::flash('login', "Confirmation link is Invalid!");
                header("Location: login.php");
            }
        }
        else
        {
            Session::flash('login', "Confirmation link is Invalid!");
            header("Location: login.php");
        }
    }
?>