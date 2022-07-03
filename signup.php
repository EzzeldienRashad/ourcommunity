<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity signup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="author" content="Ezzeldien Rashad" />
	<meta name="description" content="Sign up to OurCommunity, a community for meeting friends, sending messages, playing, etc....">
	<meta name="keywords" content="community, chat, message friends, meeting, signup, playing games" />
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

// Check if user is already logged in
include "encrypt.php";
if (isset($_SESSION["securityPassword"])) {
	[$name, $password] = decode($_SESSION["securityPassword"]);
} else if (isset($_COOKIE["securityPassword"])) {
	[$name, $password] = decode($_COOKIE["securityPassword"]);
}
if (isset($name) && isset($password)) {
	$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$stmt = mysqli_prepare($conn, "SELECT securityPassword FROM Users WHERE name = ?");
	mysqli_stmt_bind_param($stmt, "s", $name);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$info = mysqli_fetch_assoc($result);
	if ($info) {
		mysqli_stmt_free_result($stmt);
		mysqli_close($conn);
		if ($info["securityPassword"] == $password) {
			header("Location: index.php");
			exit;
		}
	}
}

// Check for errors, then add the user to the database
if (isset($_POST["submit"])) {
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];
	if (strlen($_POST["name"]) > 30) {
		$_SESSION["nameErr"] = "*name too long";
	} else if (strlen($_POST["name"]) < 3) {
		$_SESSION["nameErr"] = "*name too short";
	} else if (!preg_match("/^[\w\d\s_]+$/", $_POST["name"])) {
		$_SESSION["nameErr"] = "*name has unallowed characters";
	} else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$_SESSION["emailErr"] = "*Email not valid";
	} else {
		$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
		$stmt = mysqli_prepare($conn, "SELECT * FROM Users WHERE name = ?");
		mysqli_stmt_bind_param($stmt, "s", $_POST["name"]);
		mysqli_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);	
		$info = mysqli_fetch_all($result, MYSQLI_ASSOC);
		if ($info) {
			$_SESSION["nameErr"] = "*name already used by another user";
		} else {
			$stmt = mysqli_prepare($conn, "SELECT * FROM Users WHERE email = ?");
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
				exit;
			}
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
		<div class="password-cont">
			<input type="password" name="password" placeholder="password" style="padding-right: 40px;" autocomplete="new-password" value="<?php if (isset($_SESSION["password"])) echo $_SESSION["password"]; ?>" />
			<span class="password-eye">&#128065;</span>
			<div id="passStrengthInfo" class="err"></div>
		</div>
		<input type="submit" name="submit" class="submit" value="Sign Up" />
	</form>
	<a class="login-redirect" href="login.php">Already have an account?</a>
</div>

</main>
<footer>
<a href="signup.php">sign up</a>
<a href="login.php">log in</a>
<br /><br />
&copy; Ezzeldien 2022 - <?php echo date("Y") ?>
</footer>
</body>
</html>