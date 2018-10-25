<?php
	require_once('db.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT * FROM domains";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        echo '<li id="domain-'.$row['id'].'"><div style="float:left;">';
	        echo $row['id'].' - ';
	        echo '<a target="_blank" href="http://'.$row['server_name'].'">';
	        echo $row['server_name'].' ';
	        echo '</a>';
	        echo (!empty($row['server_alias'])?'<i>('.$row['server_alias'].')</i>':'');
	        echo '</div>';
	        echo '<div style="float:right;">';
	        echo '<i class="fa fa-trash" onClick="deleteDomain('.$row['id'].')" style="cursor:pointer;"></i>';
	        echo '</div>';
	        echo '&nbsp;</li>';
	    }
	} else {
	    echo json_encode("");
	}
	print_r($result->fetch_assoc());
	$conn->close();
?>