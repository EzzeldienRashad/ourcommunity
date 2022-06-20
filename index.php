<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="description" content="a community for meeting friends, sending messages, chating, etc....">
	<meta name="keywords" content="community chat message friends meeting">
	<script type="text/javascript" src="scripts/idnex.js" defer></script>
	<link rel="icon" href="pictures/community_logo.webp">
	<link rel="stylesheet" href="styles/index.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet">
</head>
<body>
<header>

</header>
<main>

<?php
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
	$stmt = mysqli_prepare($conn, "SELECT securityPassword FROM Users WHERE name = ?");
	mysqli_stmt_bind_param($stmt, "s", $name);
	mysqli_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);	
	$info = mysqli_fetch_assoc($result);
	if ($info) {
		mysqli_stmt_free_result($stmt);
		mysqli_close($conn);
		if ($info["securityPassword"] != $password) {
			header("Location: login.php");
			exit;
		}
	} else {
		header("Location: login.php");
		exit;
	}
} else {
	header("Location: login.php");
	exit;
}
?>

</main>
<footer>
<a href="signup.php">sign up</a>
<a href="login.php">log in</a>
<br /><br />
&copy; Ezzeldien 2022
</footer>
</body>
</html>