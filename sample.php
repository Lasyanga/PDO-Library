<?php
require_once('./class_lib/autoload.php');

	$sample = new PDODatabase();

	$sql_query = "select * from person";
	// $sql_query = "select from person";

	// $sql_query = "INSERT INTO person(fname, lname, mname) VALUES(?, ?, ?)";

	// $param = array('Herminigildo', 'Quiano', 'Alcover');

	$sample->pdoExecuteQuery($sql_query);

	echo $sample->getRowCount();
	echo $sample->get_isQuerySuccess();
	echo "\n";
	print_r($sample->getFetchData());
?>
