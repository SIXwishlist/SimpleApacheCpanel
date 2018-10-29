<!DOCTYPE html>
<html>

<head>
	<title>Simple Apache CPanel - Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-win8.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<div class="w3-container w3-win8-crimson w3-center">
	<h1>Login to <?php echo $_SERVER['SERVER_ADDR']; ?></h1>
</div>

<div class="w3-row">
	<div class="w3-col m1">&nbsp;</div>
	<div class="w3-col m10">
		<div class="w3-ul w3-card-4" style="font-size:large;">
			<div class="w3-container">
				<form method="POST" action="login-post.php">
					<h2>Login</h2>
					<p>
						<label>Username</label>
						<input class="w3-input" type="text" id="username" name="username">
					</p>
					<p>
						<label>Password</label>
						<input class="w3-input" type="password" id="password" name="password">
					</p>
					<p class="w3-center">
						<button class="w3-btn w3-win8-crimson" type="submit">Login</button>
					</p>
				</form>
			</div>
		</div>
	</div>
	<div class="w3-col m1">&nbsp;</div>
</div>


<script>

</script>

</body>
</html>