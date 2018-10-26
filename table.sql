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

#db:   simple_cpanel
#user: simple_cpanel_user
#pass: x1jjvkfhbrThhfdnQehdclPp35D5ndhdjj63QP5