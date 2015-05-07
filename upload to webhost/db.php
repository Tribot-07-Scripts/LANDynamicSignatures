<?php
//DO NOT EDIT THE GLOBALS

global $table_name;
global $mysqli;
global $privateKey;
global $iv;

//SET UP YOUR DATABASE CONNECTION

$host = "";
$user = "";
$pass = "";
$db_name = "";
$table_name = "";

// THESE KEYS SHOULD BE THE SAME AS IN JAVA (both should be 16bit)

$privateKey = "";
$iv = "";

//DO NOT EDIT BELOW THIS LINE

$mysqli = mysqli_connect($host, $user, $pass, $db_name);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

?>