<!DOCTYPE html>
<html>
<head>
	<title>CAMAGRU</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/profile.css">
</head>
<?php
    require_once 'core/init.php';
    $user = new User();

    if (!$user->isLoggedIn())
    {
        Session::flash('general', "You need to be logged in!");
        header("Location: index.php");
    }
    
    if (Input::exists())
    {
        if (Token::check(input::get('token')))
        {
            
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'email' => array(
                    'type' => 'email',
                    'max' => 60,
                    'unique' => 'users'
                ),
                'confirm_email' => array(
                    'matches' => 'email'
                )
            ));

            if ($validation->passed())
            {
                $receive = (Input::get('de_activate') === 'on') ? true : false;
                if ($user->data()->RECEIVE == 0 && $receive == true)
                {
                    $receive = 1;
                }
                else if ($user->data()->RECEIVE == 1 && $receive == true)
                {
                    $receive = 0;
                }
                try
                {
                    if (Input::get('email'))
                    {
                        $user->update(array(
                            'EMAIL' => Input::get('email'),
                            'RECEIVE' => ($receive == false) ? $user->data()->RECEIVE : $receive
                        ));
                    }
                    else if (Input::get('de_activate') === 'on')
                    {
                        $user->update(array(
                            'RECEIVE' => $receive
                        ));
                    }
                }
                catch (Exception $e)
                {
                    die($e->getMessage());
                }
                Session::flash('profile', "EMAIL settings changed Succesfully!");
                header("Location: profile.php");
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
		<div class="intro">
			<h1 class="heading">Change Email Settings</h1>
		</div>
	</div>
    <section>
        <div class="login-form">    
            <form action="" method="POST">
                <input type="email" name="email" id="email" placeholder="Enter New Email" value="<?php echo escape(Input::get('email')); ?>">
                <input type="email" name="confirm_email" placeholder="Enter Email Again" value="<?php echo escape(Input::get('confirm_email')); ?>">
                <label for="de_activate"><input type="checkbox" name="de_activate" id="de_activate">De/Activate Email Notifications</label> 
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" name="submit" value="Update">
            </form>
        </div>
    </section>
</body>
<?php
	include_once 'includes/footer.php';
?>