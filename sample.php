<?php
require_once('./class_lib/autoload.php');

	$sample = new PDODatabase();

	$sql_query = "select * from user_account";

	$param[0] = "gil";
	$param[1] = "nickpassword";

	$sample->pdoExecuteQuery($sql_query);

	echo $sample->getRowCount();
	print_r($sample->getFetchRow());
?>
