<!DOCTYPE html>
<html>
<head>
	<title>Take Snap</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/booth.css" />
<head>
<body>
	
<?php
require_once 'core/init.php';

$user = new User();
if($user->isLoggedIn())
{
?>
	<div>
		<h1 class="heading">cAmagru<h1>
		<div class="menu_container">
			<div class="menu">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="profile.php">Profile</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div>
			<div class="intro">
				<p></p>
				<h1 class="heading">PHOTO BOOTH<h1>
				<p></p>
			</div>
		</div>
	</div>
<?php
}
else
{
	header("Location: login.php");
	Session::flash('login', 'You need to be Logged in!');
}

if(Session::exists('takesnap'))
{
	alert(Session::flash('takesnap'));
}
if(Session::exists('takesnap_errors'))
{
	alert(Session::flash('takesnap_errors'));
}
?>
	<section>
		<br>
		<div class="booth">
			<div class="photo_area">
				<div class="webcam">
					<video class="videoslot" id="video" width="640px" height="480px" autoplay="true"></video>
				</div>
				<div class="takesnap_button">
					<button id="takesnap">Take Snap!</button>
				</div>
				<div class="canvas_area">
					<canvas id="snap" width="640px" height="480px"></canvas>
				</div>
			</div>
			<div class="upload_form">
				<form action="image_upload.php" method="POST" enctype="multipart/form-data">
					<h3>SELECT STICKER:</h3><br>
					<div class="cc-selector">
						<input type="checkbox" name="sticker[]" checked="checked" id="none" value="none" />
						<label class="sticker-cc none" for="none"></label><br>
						<input type="checkbox" name="sticker[]" id="balloons" value="balloons>http://www.pngmart.com/files/1/Balloons-PNG-Free-Download.png" />
						<label class="sticker-cc balloons" for="balloons"></label><br>
						<input type="checkbox" name="sticker[]" id="groot" value="groot>https://img00.deviantart.net/bfc1/i/2017/280/c/f/baby_groot_1___transparent_by_captain_kingsman16-dbpt2qy.png" />
						<label class="sticker-cc groot" for="groot"></label><br>
						<input type="checkbox" name="sticker[]" id="dice" value="dice>https://i1.wp.com/www.mpt-barsuraube.fr/wp-content/uploads/2017/10/dice.png" />
						<label class="sticker-cc dice" for="dice"></label><br>
						<input type="checkbox" name="sticker[]" id="apple" value="apple>http://iconbug.com/data/cd/256/831cb32d8d85448b87fc70d3a2e58ff3.png" />
						<label class="sticker-cc apple" for="apple"></label><br>
					</div>
					<div class="form_rest">
						<h3>WEBCAM UPLOAD AREA:</h3>
						<input type="text" id="baseimg" name="baseimg" value="" style="display: none"><br>
						<input type="text" name="title" id="title" placeholder="Enter a Title"><br>
						<input type="text" id="comment" name="comment" placeholder="Enter a Comment"><br>
						<input type="submit" name="web_upload" id="submit" value="Upload Web"><br>
						<br><h3>IMAGE UPLOAD AREA:</h3><br>
						<input type="file" name="image" id="image"><br>
						<input type="text" name="uploadtitle" id="title" placeholder="Enter a Title"><br>
						<input type="text" name="uploadcomment" id="comment" placeholder="Enter a Comment"><br>
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						<input type="submit" name="image_upload" id="submit" value="Upload Image">
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript">
			(function() {
				var video = document.getElementById('video'),
					canvas = document.getElementById('snap'),
					context = canvas.getContext('2d'),
					vendorURL = window.URL || window.webkitURL;
				
				navigator.getMedia =    navigator.getUserMedia ||
										navigator.webkitGetUserMedia ||
										navigator.mozGetUserMedia;
				navigator.getMedia({
					video: true,
					audio: false
				}, function(stream) {
					video.src = vendorURL.createObjectURL(stream);
					video.play();
				}, function(error) {
					alert("Unable to stream webcam media")
				});

				document.getElementById('takesnap').addEventListener('click', function()
				{
					context.drawImage(video, 0, 0, 640, 480);
					var image = canvas.toDataURL("image/png");
					var base_img = document.getElementById('baseimg');
					base_img.value = image;
				});
			})();
		</script>
	</section>
	<div class="edits_frame">
		<h1 class="heading">Edit Images</h1>
		<iframe src="editpics.php?">
			<p>Your browser does not support iframes</p>
		</iframe>
	</div>
<?php
include_once 'includes/footer.php';
?>