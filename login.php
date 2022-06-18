<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="description" content="a community for meeting friends, sending messages, chating, etc....">
	<meta name="keywords" content="community chat message friends meeting">
	<script src="scripts/login.js" defer></script>
	<link rel="icon" href="pictures/community_logo.webp">
	<link rel="stylesheet" href="styles/login.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet">
</head>
<body>
<main>

<?php 
// setcookie("encPassword", '', time() -3600, "/");
// unset($_SESSION["encPassword"]);
include "encrypt.php";
if (isset($_POST["submit"])) {
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];
	$conn = mysqli_connect("localhost", "OurCommunity", "1o2u3r4c5o@", "ourcommunity");
	$result = mysqli_query($conn, "Select * from users where email = '" . $_POST["email"] . "'");
	if (mysqli_num_rows($result)) {
		$info = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		mysqli_close($conn);
		if ($info["password"] != $_POST["password"]) {
			$_SESSION["passwordErr"] = True;
		} else {
			$encPassword = encode($info["name"]);
			$_SESSION["encPassword"] = $encPassword;
			if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
				setcookie("encPassword", $encPassword, time() + 86400 * 30, "/");
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
		<div id="emailErr"><?php if (isset($_SESSION["emailErr"])) {echo "Wrong Email"; unset($_SESSION["emailErr"]);} ?></div>
		<input type="password" name="password" placeholder="Password" autocomplete="current-password" style="<?php if (isset($_SESSION["passwordErr"])) echo "border-color: red"; ?>" value="<?php if (isset($_SESSION["password"])) echo $_SESSION["password"]; ?>" />
		<div id="passwordErr"><?php if (isset($_SESSION["passwordErr"])) {echo "Wrong Password"; unset($_SESSION["passwordErr"]);} ?></div>
		<input type="submit" name="submit" class="submit" value="log in" />
		<div class="remember-div">
			<label>
				<input type="checkbox" name="remember" value="on" /> remember me 
			</label>
		</div>
	</form>
	<div class="flex">
		<hr />
		<div class="or">or</div>
	</div>
	<br />
	<a href="#">Log In</a>
	<br />
</div>

</main>
<footer>
<a href="#" lang="ar" hreflang="ar">العربية</a>
<a href="#">sign up</a>
<a href="#">log in</a>
<a href="#">about</a>
<br /><br />
&copy; Ezzeldien 2022
</footer>
</body>
</html>