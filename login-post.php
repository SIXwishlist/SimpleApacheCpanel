<?php

	$_CORRECT_USERNAME = 'admin';
	$_CORRECT_PASSWORD = '123456';

	$username = base64_encode($_POST['username']);
	$password = base64_encode($_POST['password']);

	if ($username==base64_encode($_CORRECT_USERNAME) && $password==base64_encode($_CORRECT_PASSWORD)) {
		session_start();
		$_SESSION["logged_in"] = "TRUE";
		header("Location: index.php");
	} else {
		echo '<script>alert("Incorrect username or password. Try again!");window.location="login.php";</script>';
	}

?>