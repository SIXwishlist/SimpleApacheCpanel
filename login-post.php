<?php

	require_once('db.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$username = $_POST['username'];
	$password = md5($_POST['password']);

	$sql = "SELECT * FROM users WHERE username='".$username."' AND password='".$password."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		session_start();
		$_SESSION["logged_in"] = "TRUE";
		header("Location: index.php");
	} else {
	    echo '<script>alert("Incorrect username or password. Try again!");window.location="login.php";</script>';
	}

?>