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
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'comment' => array(
                    'min' => 4,
                    'max' => 200 
                )
            ));

            if ($validation->passed())
            {
                $comment = new Comment();
                if ($user->exists())
                {
                    try
                    {
                        $comment->create(array(
                            'USERID' => $user->data()->ID,
                            'USERNAME' => $user->data()->USERNAME,
                            'COMM_ID' => Input::get('comment_id'),
                            'COMMENT' => Input::get('comment')
                        ));

                        
                        $image = new Image();
                        $from = $user->data()->USERNAME;
                        $image->retrieve(Input::get('comment_id'), 'COMM_ID');

                        $user->find($image->data()->USERID);
                        $code = $user->data()->RECEIVE;
                        if ($code == 1)
                        {
                            $owner = $user->data()->EMAIL;

                            $subject = 'Your image has been commented';

                            $message = '<html><body><br>';
                            $message .= '<a>Greetings </a><br>';
                            $message .= '<br><a>'.$from.' has commented on your image</a><br>';
                            $message .= '</body></html>';

                            $headers  = 'MIME-Version: 1.0' . "\r\n" . 
                                        'Content-type: text/html; charset=utf-8';

                            if (!mail($owner, $subject, $message, $headers))
                            {
                                Session::flash('errors', "Email failed to send");
                                header("Location: index.php");
                            }
                        }

                        Session::flash('general', "Comment Submitted Successuly");
                        header("Location: index.php");
                    }
                    catch(Exception $e)
                    {
                        die($e->getMessage());
                    }
                }
            }
            else
            {
                alert($validaton->errors());
                header("Location: index.php");
            }
        }
    }

?>