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

		// SERVER DETAILS VARIABLES
		private $server_info;
		private $server_version;
		private $server_status;

		public function __construct(){
			$this->openConnection();
		}

		public function openConnection(){
			try{
				$this->connection = new PDO("mysql:host=".$this->server_host.";dbname=".$this->database_name,
																			$this->server_username,
																			$this->server_password,
																			array(
																				PDO::ATTR_ERRMODE => true, 
																				PDO::ERRMODE_EXCEPTION => true, 
																				PDO::ATTR_PERSISTENT => true, 
																				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true)
																		);

				$this->setServerDetails(PDO::ATTR_SERVER_VERSION, PDO::ATTR_SERVER_INFO, PDO::ATTR_CONNECTION_STATUS);

			}catch(PDOException $pdoException){
				echo $pdoException->getMessage();
			}
		}

		public function pdoExecuteQuery($sql_query, $param = null, $debug = false){
			if(is_null($param)){
				try{
					$this->object = $this->connection->prepare($sql_query);

					$this->isdebug($debug);

					$this->object->execute();
				}catch(PDOException $pdoException){
					echo "PDOException: ", $pdoException->getMessage();
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

					$this->object->execute();
					$this->isdebug($param, $debug);

				}catch(PDOException $pdoException){
					echo "PDOException: ", $pdoException->getMessage();
				}
			}
		}

		public function isdebug($param = null, $debug = false){
			if($debug){
				print_r($this->object);
				var_dump($param);
				die();
			}
		}

		public function getRowCount(){
			return $this->object->rowCount();
		}

		public function getFetchRow(){
			return $this->object->fetchAll(PDO::FETCH_ASSOC);
		}

		public function setServerDetails($server_version, $server_info, $server_status){
			$this->server_version = $this->connection->getAttribute($server_version);
			$this->server_info = $this->connection->getAttribute($server_info);
			$this->server_status = $this->connection->getAttribute($server_status);
		}

		// checks if the connection is open
		public function isConnectionOpen(){
			if(!empty($this->connection) && gettype($this->connection) !== 'string')return TRUE;

			return FALSE;
		}

		public function getServerVersion(){
			return $this->server_version;
		}

		public function getServerInfo(){
			return $this->server_info;
		}

		public function getServerStatus(){
			return $this->server_status;
		}

		// closes the database connection
		public function __destruct(){
			if($this->isConnectionOpen()){
				unset($this->connection);
			}
		}

	}
?>