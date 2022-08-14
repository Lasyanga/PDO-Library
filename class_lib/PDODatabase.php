<?php
	require_once('./appconfig.php');

	class PDODatabase{

		private $server_host = SERVER_HOST;
		private $server_username = SERVER_USERNAME;
		private $server_password = SERVER_PASSWORD;
		private $database_name = SERVER_DATABASE_NAME;
		private $connection;
		private $object;
		private $data;
		private $is_query_success;

		// SERVER DETAILS VARIABLES
		private $server_info;
		private $server_version;
		private $server_status;

		/*
			__construct() will automatically perform after initialization

			Example:
				$object = new ClassName(); # __construct() will execute

				for more information about __construct() visit: https://www.php.net/manual/en/language.oop5.decon.php

		*/
		public function __construct(){
			$this->openConnection();
		}

		/*
			Attempt to create new connection to an existing database

			To check if the connection wos successful

			Example:
				$object_name = new PDODatabase();

				echo $object_name->isConnectionOpen(); // return 1 or 0. 1 == TRUE, 0 == FALSE
		*/
		public function openConnection(){
			try{
				$this->connection = new PDO(
					"mysql:host=".$this->server_host.";dbname=".$this->database_name,
					$this->server_username,
					$this->server_password,
					array(
						PDO::ATTR_ERRMODE => true, 
						PDO::ERRMODE_EXCEPTION => true, 
						PDO::ATTR_PERSISTENT => true, 
						PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
					)
				);

				$this->setServerDetails(PDO::ATTR_SERVER_VERSION, PDO::ATTR_SERVER_INFO, PDO::ATTR_CONNECTION_STATUS);

			}catch(PDOException $pdoException){
				echo $pdoException->getMessage();
				die();
			}
		}

		// checks if the connection is open
		public function isConnectionOpen(){
			if(!empty($this->connection) && gettype($this->connection) !== 'string')return TRUE;

			return FALSE;
		}

		/*
			Attempts to execute query

			Example 1:
				$object_name = new PDODatabase();

				$str_query = 'SELECT * FROM table_name';

				$object_name->pdoExecuteQuery($str_query);

				print_r($object_name->getFetchData()); # print fetch data

			Example 2:
				$object_name = new PDODatabase();

				$str_query = 'SELECT INTO table_name(fname, lname, mname) VALUES(?, ?, ?)';

				$param = array('Herminigildo Jr', 'Quiano', 'Alcover');

				$object_name->pdoExecuteQuery($str_query, $param);

				print_r($object_name->getFetchData()); # print fetch data

			@param string $sql_query 
			@param array $param
			@param bool $debug
		*/
		public function pdoExecuteQuery($sql_query, $param = null, $debug = false){
			if(is_null($param)){
				try{
					$this->object = $this->connection->prepare($sql_query);

					$this->isdebug($param, $debug);

					$this->set_isQuerySuccess($this->object->execute());

				}catch(PDOException $pdoException){
					echo $pdoException->getMessage();
					die();
				}

			}else{
				try{
					$this->object = $this->connection->prepare($sql_query);

					for($index = 0; $index < count($param); $index++){
						switch (gettype($param[$index])) {
							case 'string':
								$this->object->bindParam($index + 1, $param[$index], PDO::PARAM_STR);
								break;
							case 'integer':
									$this->object->bindParam($index + 1, $param[$index], PDO::PARAM_INT);
								break;
							case 'boolean':
									$this->object->bindParam($index + 1, $param[$index], PDO::PARAM_BOOL);
								break;
						}
					}

					$this->isdebug($param, $debug);
					$this->set_isQuerySuccess($this->object->execute());

				}catch(PDOException $pdoException){
					echo $pdoException->getMessage();
					die();
				}
			}
		}

		/*
			If debug == true, isdebug() will print_r($this->object) and print_r($param)
			and stop the process.

			@param array $param
			@param bool $debug 
		*/
		public function isdebug($param = null, $debug = false){
			if($debug){
				echo '<pre>';
				print_r($this->object);
				print_r($param);
				echo '</pre>';
				die();
			}
		}

		/*
			getRowCount() return the count of row.

			@return rowCount()
		*/
		public function getRowCount(){
			return $this->object->rowCount();
		}

		/*
			getFetchData() return associative array

			@return fetchAll()
		*/
		public function getFetchData(){
			if(!$this->is_query_success)return null;
			
			return $this->object->fetchAll(PDO::FETCH_ASSOC);
		}

		/*
			set_isQuerySuccess() set the value of $this->is_query_success coming from $this->object->execute().

			$this->object->execute() returns 1 if query execute successfully and 0 if not.

			Usage:
				$this->set_isQuerySuccess($this->object->execute());
		*/
		public function set_isQuerySuccess($success){
			$this->is_query_success = $success;
		}

		/*
			@return $this->is_query_success
		*/
		public function get_isQuerySuccess(){
			return $this->is_query_success;
		}

		/*
			Sets the server details
		*/
		public function setServerDetails($server_version, $server_info, $server_status){
			$this->server_version = $this->connection->getAttribute($server_version);
			$this->server_info = $this->connection->getAttribute($server_info);
			$this->server_status = $this->connection->getAttribute($server_status);
		}

		/*
			@return server version
		*/
		public function getServerVersion(){
			return $this->server_version;
		}

		/*
			@return server info
		*/
		public function getServerInfo(){
			return $this->server_info;
		}

		/*
			@return server status
		*/
		public function getServerStatus(){
			return $this->server_status;
		}

		/*
			Clean user inputs

			Usage:
				$object_name = new PDODatabase();

				$str = "<script>alert('Hello world')</script>";
				$object_name->clean($str);
		*/
		public function clean($input){
			$this->data = strip_tags($input);
			$this->data = trim($this->data);
			$this->data = stripslashes($this->data);
			$this->data = htmlspecialchars($this->data);
			$this->data = htmlentities($this->data);
			return $this->data;
		}

		/*
			__destruct() automatically execute when there's no other instance.

			This will automatically close the database connection.
		*/ 
		public function __destruct(){
			if($this->isConnectionOpen()){
				unset($this->connection);
			}
		}

	}
?>