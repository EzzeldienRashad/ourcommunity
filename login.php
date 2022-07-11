<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="description" content="Login to OurCommunity, a community for meeting friends, sending messages, playing, etc...." />
	<meta name="author" content="Ezzeldien Rashad" />
	<meta name="keywords" content="community, chat, message friends, meeting, login, playing games" />
	<script type="text/javascript" src="scripts/login.js" defer></script>
	<link rel="icon" href="pictures/community_logo.webp" />
	<link rel="stylesheet" href="styles/login.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet" />
</head>
<body>
<main>

<?php 
// Check if user already exists
include "encrypt.php";
if (isset($_SESSION["securityPassword"])) {
	[$name, $password] = decode($_SESSION["securityPassword"]);
} else if (isset($_COOKIE["securityPassword"])) {
	[$name, $password] = decode($_COOKIE["securityPassword"]);
}
if (isset($name) && isset($password)) {
	$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$stmt = mysqli_prepare($conn, "SELECT securityPassword FROM epiz_31976759_OurCommunity.Users WHERE name = ?");
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

// check for errors, then log user in
if (isset($_POST["submit"])) {
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];
	$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$stmt = mysqli_prepare($conn, "SELECT * FROM epiz_31976759_OurCommunity.Users WHERE email = ?");
	mysqli_stmt_bind_param($stmt, "s", $_POST["email"]);
	mysqli_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);	
	$info = mysqli_fetch_assoc($result);
	if ($info) {
		mysqli_stmt_free_result($stmt);
		mysqli_close($conn);
		if ($info["password"] != $_POST["password"]) {
			$_SESSION["passwordErr"] = True;
		} else {
			$securityPassword = encode($info["name"]);
			$_SESSION["securityPassword"] = $securityPassword;
			if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
				setcookie("securityPassword", $securityPassword, time() + 86400 * 30, "/");
			}
			header("Location: index.php");
			exit;
		}
	} else {
		$_SESSION["emailErr"] = TRUE;
	}
}

?>

<h1>OurCommunity</h1>
<div class="form">
Log in to OurCommunity<br />
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
		<input type="email" name="email" placeholder="Email address" autocomplete="email" style="<?php if (isset($_SESSION["emailErr"])) echo "border-color: red"; ?>" value="<?php if (isset($_SESSION["email"])) echo $_SESSION["email"]; ?>" />
		<div id="emailErr" class="err"><?php if (isset($_SESSION["emailErr"])) {echo "Wrong Email"; unset($_SESSION["emailErr"]);} ?></div>
		<div class="password-cont">
			<input type="password" name="password" placeholder="Password" autocomplete="current-password" style="padding-right: 40px;<?php if (isset($_SESSION["passwordErr"])) echo "border-color: red"; ?>" value="<?php if (isset($_SESSION["password"])) echo $_SESSION["password"]; ?>" />
			<span class="password-eye">&#128065;</span>
			<div id="passwordErr" class="err"><?php if (isset($_SESSION["passwordErr"])) {echo "Wrong Password"; unset($_SESSION["passwordErr"]);} ?></div>
		</div>
		<input type="submit" name="submit" class="submit" value="log in" />
		<div class="remember-div">
			<label>
				<input type="checkbox" name="remember" value="on" checked /> remember me 
			</label>
		</div>
	</form>
	<div class="relative">
		<hr />
		<div class="or">or</div>
	</div>
	<br />
	<a href="signup.php">Sign Up</a>
	<br />
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
