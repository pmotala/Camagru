<!DOCTYPE html>
<html>
<head>
	<title>CAMAGRU</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<?php
require_once 'core/init.php';

if(Session::exists('registered'))
{
	alert(Session::flash('registered'));
}
if(Session::exists('login'))
{
	alert(Session::flash('login'));
}
if(Session::exists('general'))
{
	alert(Session::flash('general'));
}
if(Session::exists('confirm'))
{
	alert(Session::flash('confirm'));
}
if(Session::exists('errors'))
{
	alert(Session::flash('errors'));
}

$user = new User();

if($user->data()->ACTIVATED === 0)
{
	Session::flash('confirm', "Please Confirm your Account!");
}
if($user->isLoggedIn())
{
?>	
<body>
	<div>
		<h1 class="heading">CaMAGRU<h1>
		<div class="menu_container">
			<div class="menu">
				<ul>
					<li><a href="logout.php">Logout</a></li>
					<li><a href="profile.php">Profile</a></li>
					<li><a href="takesnap.php">Take Snap!</a></li> 
				</ul>
			</div>
		</div>
		<div class="intro">
			<a class="name">Hello <?php echo escape($user->data()->USERNAME); ?></a>
			<h1 class="heading">GALLERY<h1>
		</div>
	</div>
<?php
	}
	else
	{
		print '<div class="menu_container">';
		print '	<div class="menu_logout">';
		print '		<ul class="logout_ul">';
		print '			<li><a href="login.php">Login</a></li>';
		print '			<li><a href="signup.php">Signup</a></li>';
		print '		</ul>';
		print '	</div>';
		print '</div>';
	}
?>
	<section class="main-container">
		<div class="main-wrapper">
		</div>
		<div>
			<?php
				require_once 'includes/results.php';
			?>
		</div>
	</section>
<?php
	include_once 'includes/footer.php';
?>