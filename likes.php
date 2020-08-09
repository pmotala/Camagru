<?php
    require_once 'core/init.php';
    
    if (Input::exists())
    {
        if (Token::check(Input::get('token')))
        {      
            $user = new User();
            if (!$user->isLoggedIn())
            {
                Session::flash('login', "You need to be logged in!");
                header("Location: login.php");
            }
            $db = Database::getInstance();
            $sql = "SELECT * FROM likes WHERE COMM_ID = ? AND USERID = ?";
            $likes = $db->query($sql, array(Input::get('comment_id'), $user->data()->ID));
            if (!$likes->getCount())
            {
                $likes->insert('likes', array(
                    'USERID' => $user->data()->ID,
                    'COMM_ID'=> Input::get('comment_id'),
                    'LIKES' => (empty(Input::get('like'))) ? 0 : 1,
                    'DISLIKES' => (empty(Input::get('dislike'))) ? 0 : 1
                ));
                if (!empty(Input::get('like')))
                {
                    Session::flash('general', "Succesfully Liked!");
                    header("Location: index.php");
                }
                else if (!empty(Input::get('dislike')))
                {
                    Session::flash('general', "Succesfully Disliked!");
                    header("Location: index.php");
                }
            }
            else if ($likes->getCount())
            {
                if ($likes->first()->LIKES == 0 && !empty(Input::get('like')))
                {
                    $likes->queryNormal("UPDATE likes SET LIKES = ?, DISLIKES = ? WHERE COMM_ID = ? AND USERID = ?", array(1, 0, Input::get('comment_id'), $user->data()->ID));
                    Session::flash('general', "Image Liked Successfully!");
                    header("Location: index.php");
                }
                else if ($likes->first()->DISLIKES == 0 && !empty(Input::get('dislike')))
                {
                    $likes->queryNormal("UPDATE likes SET DISLIKES = ?, LIKES = ? WHERE COMM_ID = ? AND USERID = ?", array(1, 0, Input::get('comment_id'), $user->data()->ID));
                    Session::flash('general', "Image Disliked Successfully!");
                    header("Location: index.php");
                }
                else
                {
                    if (!empty(Input::get('like')))
                    {
                        Session::flash('general', "Image already Liked!");
                        header("Location: index.php");
                    }
                    else if (!empty(Input::get('dislike')))
                    {
                        Session::flash('general', "Image already Disliked!");
                        header("Location: index.php");
                    }
                }
            }
            else
            {
                alert($likes->error());
                Session::flash('general', "Failed!");
                header("Location: index.php");
            }

        }
    }
?>