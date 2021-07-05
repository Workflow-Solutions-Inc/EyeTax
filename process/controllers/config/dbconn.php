<?php
$currentDateTime = date('y-M-d h:i a');
date_default_timezone_set('Asia/Manila');

$host="156.67.217.132";
$port=3306;
$socket="";
$user="moneycat";
$password="moneycat2021";
$dbname="eyetax_dev";

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

//$conn->close();

							
?>