<!DOCTYPE html>
<html>
<head>
	<title>CAMAGRU</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
</head>
<?php
    require_once 'core/init.php';
    
    if (Input::exists())
    {
        if (Token::check(Input::get('token')))
        {
           $validate = new Validate();
           $validation = $validate->check($_POST, array(
               'username' => array(
                   'min' => 4,
                   'max' => 22
               )
           ));

           if ($validation->passed())
           {
                $user = new User();
                $user->find(Input::get('username'));

                if ($user->exists())
                {
                    $token = Token::generate();
                    $dir = dirname(__FILE__);
                    $split = explode('htdocs', $dir);
                    $dir = $split[1];
                    
                    $user->update(array(
                        'TOKENS' => $token
                    ), $user->data()->ID);

                    $username = Input::get('username');

                    $to = $user->data()->EMAIL;
					$subject = "Reset Password";

					$message = '<html><body>';
					$message .= '<a>Greetings '.$username.'</a><br>';
					$message .= '<a>Follow the link below to Reset your password:</a><br>';
					$message .=	'<a href="http://localhost:8080'.$dir.'/resetpassword.php?code='.$token.'&user='.$username.'">Reset Password</a>';
					$message .= '</body></html>';

					$headers  = 'MIME-Version: 1.0' . "\r\n" . 
								'Content-type: text/html; charset=utf-8';

					if (mail($to, $subject, $message, $headers))
					{
						Session::flash('login', "Please check your email, for Reset Link!");
						header("Location: login.php");
					}

                }
                else
                {
                   alert("User Does not Exist");
                }
           }
           else
           {
               alert($validation->errors());
           }
        }
    }

?>
<body>
    <div>
		<h1 class="heading">CaMAGRU<h1>
		<div class="menu_container">
			<div class="menu">
				<ul>
                    <li><a href="signup.php">Signup</a></li>
                    <li><a href="login.php">Login</a></li>
					<li><a href="index.php">Home</a></li> 
				</ul>
			</div>
        </div>
	</div>
    <section>
        <div class="login-form">
            <form action="" method="POST">
                <h2>Forgot Password</h2>
                <input type="text" name="username" id="username" placeholder="Enter Username" value="<?php echo escape(input::get('username')); ?>">
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" name="submit" value="Reset">
            </form>
        </div>
    </section>
</body>
<?php
	include_once 'includes/footer.php';
?>