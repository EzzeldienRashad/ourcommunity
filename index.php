<?php
session_start();
include "encrypt.php";
if (isset($_SESSION["securityPassword"])) {
	[$name, $password] = decode($_SESSION["securityPassword"]);
} else if (isset($_COOKIE["securityPassword"])) {
	[$name, $password] = decode($_COOKIE["securityPassword"]);
} else {
	header("Location: login.php");
	exit;
}
if (isset($name) && isset($password)) {
	$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$stmt = mysqli_prepare($conn, "Select securityPassword from Users where name = ?");
	mysqli_stmt_bind_param($stmt, "s", $name);
	mysqli_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);	
	$info = mysqli_fetch_assoc($result);
	if ($info) {
		mysqli_stmt_free_result($stmt);
		mysqli_close($conn);
		if ($info["securityPassword"] != $password) {
			echo "<h1>FAILURE!</h1>" . $password . 'tttt' . $info["securityPassword"];
		} else {
			echo "<h1>Hello, $name!</h1>";
		}
	} else {
		echo "no info";
	}
} else {
	echo "not set";
}
?>