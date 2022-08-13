<?php
	
	/*
		Setting the defualt timezone, for more valid timezones:

		https://www.php.net/manual/en/timezones.php
		
	*/
	$time_zone = "Asia/Manila"; # set timezone

	$x_host = "localhost"; # database server host 
	$x_username = "root"; # database server username
	$x_password = ""; # database server password
	$x_dbname = "database_name"; # database name

	// $x_is_Errors = 1; # display errors? 1 == TRUE, 0 == FALSE

	//DEFAULT TIMEZONE
	ini_set("date.timezone", $time_zone);

	//DISPLAY ERRORS
	// ini_set('display_errors', $x_is_Errors);
	// ini_set('display_startup_errors', $x_is_Errors);


	/*
		Defining the constanst variables
	*/

	//DIRECTORY SEPARATOR
	defined('DS') ? null: define('DS', DIRECTORY_SEPARATOR);

	//DIRECTORY PATH
	defined('BASE_URI') ? null : define('BASE_URI', dirname(__FILE__).DS);

	//SERVER HOST
	defined('SERVER_HOST') ? null : define('SERVER_HOST', $x_host);

	//SERVER USERNAME
	defined('SERVER_USERNAME') ? null : define('SERVER_USERNAME', $x_username);

	//SERVER PASSWORD
	defined('SERVER_PASSWORD') ? null : define('SERVER_PASSWORD', $x_password);

	//SERVER DATABASE
	defined('SERVER_DATABASE_NAME') ? null : define('SERVER_DATABASE_NAME', $x_dbname);

	// echo realpath(dirname(__FILE__));

?>