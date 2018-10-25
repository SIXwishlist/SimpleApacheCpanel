<?php
	require_once('db.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "INSERT INTO domains (`server_name`, `server_alias`, `wordpress`, `done`) VALUES ('".$_POST['server_name']."', '".$_POST['server_alias']."', '".$_POST['wordpress']."', '".$_POST['done']."')";
	$result = $conn->query($sql);

	$conn->close();
?>