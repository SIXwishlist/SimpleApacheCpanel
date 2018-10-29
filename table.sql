#create these database and user privileges
#db:   simple_cpanel
#user: simple_cpanel_user    #this user should have admin/root privileges
#pass: x1jjvkfhbrThhfdnQehdclPp35D5ndhdjj63QP5

use simple_cpanel;

CREATE TABLE domains (
	id int NOT NULL AUTO_INCREMENT,
	server_name varchar(255) NOT NULL,
	server_alias varchar(255),
	wordpress varchar(8) NOT NULL,
	done varchar(8) NOT NULL,
	db_password varchar(32),
	writable varchar(8) NOT NULL,
	PRIMARY KEY (ID)
);

CREATE TABLE users (
	id int NOT NULL AUTO_INCREMENT,
	username varchar(64) NOT NULL,
	password varchar(64),
	PRIMARY KEY (ID)
);
#when adding users to users table, make sure the password is md5