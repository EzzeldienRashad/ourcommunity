<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity users</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="description" content="a community for meeting friends, sending messages, chating, etc....">
	<meta name="keywords" content="community chat message friends meeting">
	<script type="text/javascript" src="scripts/index.js" defer></script>
	<script src="https://kit.fontawesome.com/5cf0e9fc67.js" crossorigin="anonymous"></script>
	<link rel="icon" href="pictures/community_logo.webp">
	<link rel="stylesheet" href="styles/users.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet">
</head>
<body>
<header>

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

if (isset($_POST["logout"])) {
	unset($_SESSION["securityPassword"]);
	setcookie("securityPassword", $securityPassword, time() - 3600, "/");
	header("Location: login.php");
}
?>
<span class="decoration"></span>
<h1>OurCommunity</h1>
<div class="hello"><?php echo "Hello, <span>" . $name . "</span>!"; ?></div>
<div class="menu">
	<span></span>
	<span></span>
	<span></span>
</div>
<div class="dropdown">
<a href="#">other users</a>
<form class="logout" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
	<input type="submit" name="logout" value="logout" />
</form>
</div>
</header>
<main>

<h2>Other users:</h2>
<?php
$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
$result = mysqli_query($conn, "SELECT * FROM Users");
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
foreach ($users as $user) {
	if ($user["name"] != $name) {
		echo "<div class='user'>" . $user["name"] . "</div>";
	}
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