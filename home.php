<!doctype html>
<html>
<head>
	<title>College Event Website</title>
	<meta name="description" content="COP4710 Spring 2022" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Optima" />
	<style>
		#page {
			max-width: 800px;
			margin: 0px auto;
			padding: 0px 40px;
		}
		.logo { margin-bottom: 40px; font-family: Optima; font-size: 50px; font-style: normal; font-variant: normal; font-weight: 500; line-height: 25px; }
		p.body { font-family: Optima; font-size: 20px; font-style: normal; font-variant: normal; font-weight: 300; line-height: 35px; }
		.paragraph {
			font-size: 18pt;
		}
		.footer {
			text-align: center;
			max-width: 650px;
			margin: 0px auto;
		}
		body {
			font-family: "optima", sans-serif;
			color: black;
		}
	</style>
</head>
<body>

	<div id="page">
		<br><br><center><div class="logo"><a href="index.php" style="text-decoration: none; color: black;">College Event Manager</a></div></center>		

		<br>
		<?php if(isset($_SESSION['userid'])): ?>
            <center><p class="body">
				<h3>Join RSO</h3>
				<form action='joinRSO.php' method='post'>
					<button type='submit' name='join_rso' style='height:50px; width:100px'>Join</button>
    			</form>

			</p></center>

            <center><p class="body">
		
				<h3>Create RSO</h3>
				<form action='createRSO.php' method='post'>
					<button type='submit' name='create_rso' style='height:50px; width:100px'>Create</button>
    			</form>

			</p></center>
		
		<?php endif; ?>
		
	</div>
</body>
</html>