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
		header("Location: login.php");
		Session::flash('login', 'You need to be Logged in!');
	}

	if(Session::exists('profile'))
	{
		alert(Session::flash('profile'));
	}
?>
<body>
	<div>
		<h1 class="heading">CaMAGRU<h1>
		<div class="menu_container">
			<div class="menu">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
		<div class="intro">
			<h1 class="heading">USER PROFILE</h1>
		</div>
	</div>
	<br><a>Current Username: <?php echo escape($user->data()->USERNAME); ?></a><br>
	<br><a>Current Email: <?php echo escape($user->data()->EMAIL); ?></a><br>
	<?php
		if ($user->data()->RECEIVE == 1)
		{
			print '<br><a>Receive E-mail Notifications: Active</a><br>';
		}
		else if ($user->data()->RECEIVE == 0)
		{
			print '<br><a>Receive E-mail Notifications: Deactivated</a><br>';
		}
	?>
	<br>
	<div class="menu_container">
		<div class="menu-settings">
			<div>
				<a class="menu_li" href="changepassword.php">Change Password</a><br>
				<a class="menu_li" href="changeusername.php">Change Username</a><br>
				<a class="menu_li" href="changeemail.php">Change Email settings</a><br>
			</div>
		</div>
	</div>
</body>
<?php
	include_once 'includes/footer.php';
?>