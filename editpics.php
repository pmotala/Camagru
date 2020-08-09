<!DOCTYPE html>
<html>
<head>
	<title>CAMAGRU</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/edit.css">
</head>
<?php
	require_once 'core/init.php';
	
	$user = new User();

	if(!$user->isLoggedIn())
	{
		header("Location: login.php");
		Session::flash('login', 'You need to be Logged in!');
	}

	if(Session::exists('edit'))
	{
		alert(Session::flash('edit'));
	}
?>
<body>
	<?php
		require_once 'includes/editresults.php';
	?>
</body>
</html>