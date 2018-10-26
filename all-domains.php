<?php
	require_once('db.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM domains WHERE done!='deleted'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        echo '<li id="domain-'.$row['id'].'"><div style="float:left;">';
			echo ($row['done']=='false')?'NOT YET READY (wait for 1 minute) ':'OK ';
			echo ($row['wordpress']=='true')?'<img src="images/wp-logo.png" style="height:20px;margin-top:-4px;" />':'';
			echo ' | ';
	        echo '<a target="_blank" href="http://'.$row['server_name'].'">';
	        echo $row['server_name'];
	        echo '</a> ';
	        echo (!empty($row['server_alias'])?'<i>('.$row['server_alias'].')</i>':'');
	        echo '</div>';
			echo '<div style="float:right;">';
			echo '<span style="cursor:pointer;"><i class="fa fa-cubes"></i> Database</span>';
			echo ' | ';
			if ($row['writable']=='true') {
				echo '<span onClick="changeWritable(\'false\', '.$row['id'].')" style="cursor:pointer;" id="writable-'.$row['id'].'"><i title="This website is not secure because it is writable to the webserver. Click this icon to secure." class="fa fa-unlock"></i> Secure Now</span>';
			} else {
				echo '<span onClick="changeWritable(\'true\', '.$row['id'].')" style="cursor:pointer;" id="writable-'.$row['id'].'"><i title="This website is secure. Click here to make this writable." class="fa fa-lock"></i> Make Writable</span>';
			}
			echo ' | ';
	        echo '<i class="fa fa-trash" onClick="deleteDomain('.$row['id'].')" style="cursor:pointer;"></i>';
	        echo '</div>';
	        echo '&nbsp;</li>';
	    }
	} else {
	    echo '';
	}
	print_r($result->fetch_assoc());
	$conn->close();
?>