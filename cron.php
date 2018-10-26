<?php

	require_once('db.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM domains WHERE done='false'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        createVirtualHost($row);
	    }
	}

	function createVirtualHost($domain) {
		echo $domain['server_name'] . "\n";
		shell_exec("cat '' >> ".$domain['server_name'].".conf");
	}

?>