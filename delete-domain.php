<?php
	require_once('db.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE domains SET done='deleted' WHERE id=".$_POST['id'];
	$result = $conn->query($sql);

	print_r($result->fetch_assoc());
	$conn->close();
?>