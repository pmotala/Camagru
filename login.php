<!DOCTYPE html>
<html>
<head>
	<title>CAMAGRU</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
</head>
<?php
    require_once 'core/init.php';
    
    if(Session::exists('login'))
	{
		alert(Session::flash('login'));
    }
    
    if (Input::exists())
    {
        if (Token::check(Input::get('token')))
        {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'username' => array(
                    'required' => true,
                    'min' => 4,
                    'max' => 22
                ),
                'password' => array(
                    'required' => true,
                    'min' => 8,
                    'max' => 20
                )
            ));

            if ($validation->passed())
            {
                $user = new User();
                $username = Input::get('username');
                $password = Input::get('password');
                $remember = (Input::get('remember') === 'on') ? true : false;
                $login = $user->login($username, $password, $remember);
                
                if ($login)
                {
                    if ($user->data()->ACTIVATED == 1)
                    {
                        Session::flash('login', "SUCCESSFULLY LOGGED IN!");
                        header("Location: index.php");
                    }
                    else
                    {
                        alert("login Failed! Please Confirm Account");
                    }
                }
                else
                {
                    alert("Login Failed! Incorrect Details");
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
					<li><a href="index.php">Home</a></li> 
				</ul>
			</div>
        </div>
	</div>
    <section>
        <div class="login-form">
            <h2>Login</h2>
            <form action="" method="POST">
                <input type="text" name="username" id="username" placeholder="Enter Username" value="<?php echo escape(input::get('username')); ?>">
                <input type="password" name="password" id="passord" placeholder="Enter your Password" value=""><br>
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <label for="remember"><input type="checkbox" name="remember" id="remember">Remember me</label> 
                <input type="submit" name="submit" value="login">
                <a href="forgotpassword.php">Forgot Password</a>
            </form>
        </div>
    </section>
</body>
<?php
	include_once 'includes/footer.php';
?>