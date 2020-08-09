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

    if(!$user->isLoggedIn())
    {
        Session::flash('general', 'You need to be logged in');
        header("Location: index.php");
    }

    if (Input::exists())
    {
        if (Token::check(Input::get('token')))
        {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'current_password' => array(
                    'required' => true,
                    'min' => 8,
                    'max' => 20
                ),
                'new_password' => array(
                    'required' => true,
                    'strength' => true,
                    'min' => 8,
                    'max' => 20
                ),
                'confirm_password' => array(
                    'required' => true,
                    'min' => 8,
                    'max' => 20,
                    'matches' => 'new_password'
                )
            ));

            if ($validation->passed())
            {
                if (!Hash::verify(Input::get('current_password'), $user->data()->PASSWORD))
                {
                    alert("The Current Password you entered is Incorrect!");
                }
                else
                {
                    try
                    {
                        $user->update(array(
                            'PASSWORD' => Hash::make(Input::get('new_password'))
                        ));

                        Session::flash('profile', 'Password has been Updated!');
                        header("Location: profile.php");
                    }
                    catch(Exception $e)
                    {
                        die($e->getMessage());
                    }
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
		<div class="intro">
			<h1 class="heading">Change Password</h1>
		</div>
	</div>
    <section>
        <div class="login-form">
            <form action="" method="POST">
                <input type="password" name="current_password" id="current_password" placeholder="Enter Current Password" value="">
                <input type="password" name="new_password" id="new_password" placeholder="Enter New Password" value="">
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