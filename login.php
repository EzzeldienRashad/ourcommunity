<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="description" content="a community for meeting friends, sending messages, chating, etc....">
	<meta name="keywords" content="community chat message friends meeting">
	<script type="text/javascript" src="scripts/login.js" defer></script>
	<link rel="icon" href="pictures/community_logo.webp">
	<link rel="stylesheet" href="styles/login.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet">
</head>
<body>
<main>

<?php 
// setcookie("securityPassword", '', time() -3600, "/");
// unset($_SESSION["securityPassword"]);	

include "encrypt.php";
if (isset($_POST["submit"])) {
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];
	$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$stmt = mysqli_prepare($conn, "Select * from Users where email = ?");
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
		<input type="password" name="password" placeholder="Password" autocomplete="current-password" style="<?php if (isset($_SESSION["passwordErr"])) echo "border-color: red"; ?>" value="<?php if (isset($_SESSION["password"])) echo $_SESSION["password"]; ?>" />
		<div class="err"><?php if (isset($_SESSION["passwordErr"])) {echo "Wrong Password"; unset($_SESSION["passwordErr"]);} ?></div>
		<input type="submit" name="submit" class="submit" value="log in" />
		<div class="remember-div">
			<label>
				<input type="checkbox" name="remember" value="on" checked /> remember me 
			</label>
		</div>
	</form>
	<div class="flex">
		<hr />
		<div class="or">or</div>
	</div>
	<br />
	<a href="signup.php">Sign Up</a>
	<br />
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
</html>