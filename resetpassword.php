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
                'password' => array(
                    'required' => true,
                    'min' => 8,
                    'max' => 20
                ),
                'confirm_password' => array(
                    'required' => true,
                    'min' => 8,
                    'max' => 20,
                    'matches' => 'password'
                )
            ));

            if ($validation->passed())
            {
                $user = new User();
                $user->find(input::get('user'));

                if ($user->data()->TOKENS === Input::get('code'))
                {
                    try
                    {
                        $user->update(array(
                            'PASSWORD' => Hash::make(Input::get('password'))
                        ), $user->data()->ID);
    
                        Session::flash('login', 'Password has been Updated!');
                        header("Location: login.php");
                    }
                    catch(Exception $e)
                    {
                        die($e->getMessage());
                    }    
                }
                else
                {
                    Session::flash('login', 'Link is Invalid!');
                    header("Location: login.php");
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
                    <li><a href="login.php">login</a></li>
					<li><a href="index.php">Home</a></li> 
				</ul>
			</div>
        </div>
	</div>
    <section>
        <div class="login-form">
            <form action="" method="POST">
                <h2>Reset Password</h2>
                <input type="password" name="password" id="password" placeholder="Enter New Password" value="">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Enter New Password Again" value="">
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" name="submit" value="Update">
            </form>
        </div>
    </section>
</body>
<?php
	include_once 'includes/footer.php';
?>