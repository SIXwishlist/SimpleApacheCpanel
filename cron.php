<?php

	require_once('db.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	//get all not ready websites
	$sql = "SELECT * FROM domains WHERE done='false'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		shell_exec("wget http://wordpress.org/latest.tar.gz -P /var/www/html/"); //download wordpress once
	    while($row = $result->fetch_assoc()) {
			createFolder($row);
			$db_password = createMySQLDBandUsers($row, $conn);
			createVirtualHost($row);
			updateDomainRecord($row, $db_password, $conn);
		}
		shell_exec("service apache2 restart");
		shell_exec("rm latest.tar.gz"); //remove the wordpress
	}

	//get all deleted websites
	$sql = "SELECT * FROM domains WHERE done='deleted'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			$db_name = str_replace(".", "_", $row['server_name']);
			$db_user = $db_name . "_user";
			$vhost_file = "/etc/apache2/sites-available/".$row['server_name'].".conf";
			$conn->query("DROP DATABASE " . $db_name); //delete database
			echo "\nDatabase ".$db_name." has been deleted";
			$conn->query("DROP USER '" . $db_user . "'@'localhost'"); //delete user
			echo "\nUser ".$db_user." has been deleted";
			shell_exec("a2dissite ".$row['server_name'].".conf");
			shell_exec("rm ".$vhost_file);
			echo "\nDomain ".$row['server_name']." has been removed from the web server";
			shell_exec("rm -R /var/www/html/".$row['server_name']);
			echo "\nFolder /var/www/html/".$row['server_name']." has been deleted\n";
			$conn->query("DELETE FROM domains WHERE id=".$row['id']); //delete record for this domain
		}
		shell_exec("service apache2 restart");
	}

	//update all not-writable websites
	$sql = "SELECT * FROM domains WHERE writable='false'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			shell_exec("chown -R root:root /var/www/html/".$row['server_name']);
		}
	}

	function createFolder($domain) {
		if ($domain['wordpress']=='true') {
			shell_exec("tar xfz latest.tar.gz");
			shell_exec("cp -R wordpress /var/www/html/".$domain['server_name']);
			shell_exec("chown -R www-data:www-data /var/www/html/".$domain['server_name']);
			echo "\nCreated a folder for wordpress";
		} else {
			shell_exec("mkdir /var/www/html/".$domain['server_name']);
			echo "\nCreated a folder";
		}
	}

	function createMySQLDBandUsers($domain, $conn) {
		$db_name = str_replace(".", "_", $domain['server_name']);
		$db_user = $db_name . "_user";
		$db_password = generateRandomString(8);
		$sql = "CREATE DATABASE ".$db_name;
		if ($conn->query($sql) === TRUE) {
			echo "\nDatabase ".$domain['server_name']." created successfully";
		} else {
			echo "\nError creating database: " . $conn->error;
		}
		$sql = "CREATE USER '".$db_user."'@'localhost' IDENTIFIED BY '".$db_password."';";
		if ($conn->query($sql) === TRUE) {
			echo "\nUser ".$db_user." created successfully";
		} else {
			echo "\nError creating user: " . $conn->error;
		}
		$sql = "GRANT ALL ON ".$db_name.".* TO '".$db_user."'@'localhost';";
		if ($conn->query($sql) === TRUE) {
			echo "\nUser ".$db_user." granted access to " . $db_name;
		} else {
			echo "\nError granting access to user: " . $conn->error;
		}
		return $db_password;
	}

	function createVirtualHost($domain) {
		echo "\nCreating virtual host for ".$domain['server_name'] . "\n";
		$vhost_file = "/etc/apache2/sites-available/".$domain['server_name'].".conf";
		shell_exec("echo '<VirtualHost *:80>' > ".$vhost_file);
		shell_exec("echo '\tServerName ".$domain['server_name']."' >> ".$vhost_file);
		if (!empty($domain['server_alias'])) {
			shell_exec("echo '\tServerAlias ".$domain['server_alias']."' >> ".$vhost_file);
		}
		shell_exec("echo '\tServerAdmin love.gerald@yahoo.com' >> ".$vhost_file);
		shell_exec("echo '\tDocumentRoot /var/www/html/".$domain['server_name']."' >> ".$vhost_file);
		shell_exec("echo '\n\tErrorLog \${APACHE_LOG_DIR}/".$domain['server_name']."-error.log' >> ".$vhost_file);
		shell_exec("echo '\tCustomLog \${APACHE_LOG_DIR}/".$domain['server_name']."-access.log combined' >> ".$vhost_file);
		shell_exec("echo '</VirtualHost>' >> ".$vhost_file);
		shell_exec("a2ensite ".$domain['server_name'].".conf");
	}

	function updateDomainRecord($domain, $db_password, $conn) {
		$sql = "UPDATE domains SET done='true', db_password='".$db_password."' WHERE id=".$domain['id'];
		if ($conn->query($sql) === TRUE) {
			echo "\nDomain ".$domain['server_name']." has been marked as done\n";
		} else {
			echo "\nError in updating the domain: " . $conn->error."\n";
		}
	}

	function generateRandomString($length = 16) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

?>