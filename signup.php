﻿<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity signup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="description" content="a community for meeting friends, sending messages, chating, etc....">
	<meta name="keywords" content="community chat message friends meeting signup">
	<script type="text/javascript" src="scripts/signup.js" defer></script>
	<link rel="icon" href="pictures/community_logo.webp">
	<link rel="stylesheet" href="styles/signup.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet">
</head>
<body>
<main>

<?php

include "encrypt.php";
if (isset($_POST["submit"])) {
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];
	if (strlen($_POST["name"]) > 30) {
		$_SESSION["nameErr"] = "*name too long";
	} else if (strlen($_POST["name"]) < 3) {
		$_SESSION["nameErr"] = "*name too short";
	} else if (!preg_match("/^[\w\d\s_]+$/", $_POST["name"])) {
		$_SESSION["nameErr"] = "*name has unallowed caracters";
	} else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$_SESSION["emailErr"] = "*Email not valid";
	} else {
		$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
		$stmt = mysqli_prepare($conn, "Select * from Users where email = ?");
		mysqli_stmt_bind_param($stmt, "s", $_POST["email"]);
		mysqli_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);	
		$info = mysqli_fetch_assoc($result);
		if ($info) {
			$_SESSION["emailErr"] = "*email already used by another user";
		} else {
			mysqli_stmt_free_result($stmt);
			$stmt = mysqli_stmt_init($conn);
			mysqli_stmt_prepare($stmt, "INSERT INTO Users (name, email, password, securityPassword)
			VALUES (?, ?, ?, ?)");
			$rand = rand();
			mysqli_stmt_bind_param($stmt, "sssd", $_POST["name"], $_POST["email"], $_POST["password"], $rand);
			mysqli_stmt_execute($stmt);
			$securityPassword = encode($_POST["name"]);
			$_SESSION["securityPassword"] = $securityPassword;
			header("Location: index.php");
		}
	}
}

?>

<h1>OurCommunity</h1>
<div class="form">
	Sign up to OurCommunity<br />
	<h2>Create a new account</h2>
	<span class="header-note">It's quick and easy.</span>
	<hr />
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
		<input type="text" name="name" placeholder="Full Name" autocomplete="name" style="<?php if (isset($_SESSION["nameErr"])) echo "border-color: red"; ?>" value="<?php if (isset($_SESSION["name"])) echo $_SESSION["name"]; ?>" />
		<div id="nameErr" class="err"><?php if (isset($_SESSION["nameErr"])) {echo  $_SESSION["nameErr"]; unset($_SESSION["nameErr"]);} ?></div>
		<input type="email" name="email" placeholder="email" autocomplete="email" style="<?php if (isset($_SESSION["emailErr"])) echo "border-color: red"; ?>" value="<?php if (isset($_SESSION["email"])) echo $_SESSION["email"]; ?>" />
		<div id="emailErr" class="err"><?php if (isset($_SESSION["emailErr"])) {echo  $_SESSION["emailErr"]; unset($_SESSION["emailErr"]);} ?></div>
		<input type="password" name="password" placeholder="password" autocomplete="new-password" value="<?php if (isset($_SESSION["password"])) echo $_SESSION["password"]; ?>" />
		<div id="passStrengthInfo" class="err"></div>
		<input type="submit" name="submit" class="submit" value="Sign Up" />
	</form>
</div>

</main>
<footer>
<a href="#" lang="ar" hreflang="ar">العربية</a>
<a href="signup.php">sign up</a>
<a href="login.php">log in</a>
<a href="#">about</a>
<br /><br />
&copy; Ezzeldien 2022
</footer>
</body>