<?php

require_once 'core/init.php';

if (Input::exists())
{
    if (Token::check(Input::get('token'), "edits_token"))
    {
        $user = new User();
        if ($user->isLoggedIn())
        {
            $db = Database::getInstance();
            if (Input::get('delete'))
            {
                $comm_id = trim(Input::get('comment_id'));
                $results = $db->delete('uploads', array('COMM_ID', '=', $comm_id));
                Session::flash('edit', "Image succesfully deleted!");
                header("Location: editpics.php");
            }
        }
    }
    else
    {
        Session::flash('login', "You need to be logged in!");
        header("Location: login.php");
    }
}

?>