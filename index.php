<?php
session_start();
include "encrypt.php";
if (isset($_SESSION["encPassword"])) {
	[$name, $password] = decode($_SESSION["encPassword"]);
} else if (isset($_COOKIE["encPassword"])) {
	[$name, $password] = decode($_COOKIE["encPassword"]);
} else {
	header("Location: login.php");
	exit;
}
if (isset($name) && isset($password)) {
	$conn = mysqli_connect("localhost", "OurCommunity", "1o2u3r4c5o@", "ourcommunity");
	$result = mysqli_query($conn, "Select encPassword from users where name = '" . $name . "'");
	if (mysqli_num_rows($result)) {
		$info = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		mysqli_close($conn);
		if ($info["encPassword"] != $password) {
			echo "<h1>FAILURE!</h1>";
		} else {
			echo "<h1>Hello, $name!</h1>";
		}
	}
}
?>