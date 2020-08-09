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
					'required'	=> true,
					'min'		=> 4,
					'max'		=> 22,
					'unique'	=> 'users'
				),
				'email' => array(
					'required'	=> true,
					'max'		=> 60,
					'type'		=> 'email',
					'unique'	=> 'users'
				),
				'password' => array(
					'required'	=> true,
					'min'		=> 8,
					'strength'	=> true,
					'max'		=> 20
				),
				'confirm_password' => array(
					'required'	=> true,
					'matches'	=> 'password'
				)
			));

			if ($validation->passed())
			{
				$user = new User();
				$activ_code = Token::generate();
				$dir = dirname(__FILE__);
				$split = explode('htdocs', $dir);
				$dir = $split[1];
				try
				{
					$user->create(array(
						'USERNAME'	=> Input::get('username'),
						'EMAIL'		=> Input::get('email'),
						'PASSWORD'	=> Hash::make(Input::get('password')),
						'JOINED'	=> date('Y-m-d H:i:s'),
						'ACTIVATED' => 0,
						'ACTIVATION_CODE' => $activ_code
					));

					$username = Input::get('username');

					$to = Input::get('email');
					$subject = "Account Confirmation";

					$message = '<html><body>';
					$message .= '<a>Greetings '. Input::get('username').'</a><br>';
					$message .= '<a>Please confirm your account here:</a><br>';
					$message .=	'<a href="http://localhost:8080'. $dir .'/verify.php?code='. $activ_code .'&user='. $username .'">Confirm Account</a>';
					$message .= '</body></html>';

					$headers  = 'MIME-Version: 1.0' . "\r\n" . 
								'Content-type: text/html; charset=utf-8';

					if (mail($to, $subject, $message, $headers))
					{
						Session::flash('registered', "SUCCESSFULLY REGISTERED, PLEASE CHECK YOU EMAIL TO CONFIRM ACCOUNT!");
						header("Location: index.php");
					}
				}
				catch(Exception $e)
				{
					die($e->getMessage());
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
					<li><a href="login.php">Login</a></li>
					<li><a href="index.php">Home</a></li> 
				</ul>
			</div>
        </div>
	</div>
	<section>
		<div class="login-form">
			<h2>Signup</h2>
			<form method="POST" action="">
				<input type="text" name="username" id="username" placeholder="Username" value="<?php echo escape(Input::get('username')); ?>">
				<input type="text" name="email" id="email" placeholder="E-mail" value="<?php echo escape(Input::get('email')); ?>">
				<input type="password" name="password" id="password" placeholder="Password" value="">
				<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="">
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<input type="submit" name="submit" value="Signup">
			</form>
		</div>
	</section>
<?php
	include_once 'includes/footer.php';
?>