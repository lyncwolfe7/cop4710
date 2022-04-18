<?php
include_once "header.php";
?>

<!doctype html>
<html>
	<div class="box" style="padding-bottom:150px">
		<head>
			<title class="heading2">College Event Website</title>
			<meta name="description" content="COP4710 Spring 2022" />
			<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Optima" />
			<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Permanent+Marker" />
		</head>
		
		<div class="box3">
			<body>
				<div id="page">
					<br>
					<center>
						<div class="logo">
							<a href="index.php" class="heading2-2">College Event Manager</a>
						</div>
					</center>
					<br>
					<img class="image1" src="indexPicture.webp" alt="indexPicture">
					<hr style="border: 4px solid #09C;">
					<?php if(!isset($_SESSION['user_id'])): ?>
					
						<center><p class="body">
							<br>
							<span class="heading-i">Have an account?</span>
							<form action='login.php' method='post'>
								<button type='submit' name='login' class="button-index">Login</button>
							</form>

						</p></center>
						
						<center><p class="body">
							<span class="heading-i">Don't have an account?</span>
							<form action='signup.php' method='post'>
								<button type='submit' name='signUp' class="button-index">Register</button>
							</form>
						</p></center>
					<?php endif; ?>
				</div>
			</body>
		</div>
	</div>
</html>