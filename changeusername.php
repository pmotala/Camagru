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
    $comment = new Comment();
    $image = new Image();

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
                'username' => array(
                    'required' => true,
                    'min' => 4,
                    'max' => 22,
                    'unique' => 'users'
                )
            ));

            if ($validation->passed())
            {
                try
                {
                    $user->update(array(
                        'USERNAME' => Input::get('username')
                    ));
                    $comment->update(array(
                        'USERNAME' => Input::get('username')
                    ), $user->data()->ID);
                    $image->update(array(
                        'USERNAME' => Input::get('username')
                    ), $user->data()->ID);
                }
                catch (Exception $e)
                {
                    die($e->getMessage());
                }
                Session::flash('profile', "USERNAME Changed Succesfully!");
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
			<h1 class="heading">Change Username</h1>
		</div>
	</div>
    <section>
        <div class="login-form">
            <form action="" method="POST">
                <input type="text" name="username" id="username" placeholder="Enter New Username" value="<?php echo escape(Input::get('new_username')); ?>">
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" name="submit" value="Update">
            </form>
        </div>
    </section>
</body>
<?php
	include_once 'includes/footer.php';
?>