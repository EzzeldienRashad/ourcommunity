<header>
<?php

// Check if user doesn't exist
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
	$stmt = mysqli_prepare($conn, "SELECT securityPassword FROM epiz_31976759_OurCommunity.Users WHERE name = ?");
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

// log user out if they press logout
if (isset($_POST["logout"])) {
	unset($_SESSION["securityPassword"]);
	setcookie("securityPassword", "", time() - 3600, "/");
	header("Location: login.php");
}
// Exit group if they press logout
if (isset($_POST["groupLogout"])) {
	unset($_SESSION["groupCode"]);
	setcookie("groupCode", "", time() - 3600, "/");
	header("Location: groups.php");
}
?>
<span class="decoration"></span>
<h1>OurCommunity</h1>
<div class="hello">Hello, <span><?php echo $name; ?></span>!</div>
<div class="menu">
	<span></span>
	<span></span>
	<span></span>
</div>
<div class="dropdown">
<a href="index.php">main page</a>
<a href="users.php">other users</a>
<a href="users.php?groups=true">groups</a>
<form class="logout" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">  
	<input type="submit" name="logout" value="logout" />
</form>
<form class="logout" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">  
	<input type="submit" name="groupLogout" value="Exit group" />
</form>
</div>
</header>
<script>
	// Position "Hello user!" correctly
	document.getElementsByClassName("hello")[0].style.left =
		(document.getElementsByClassName("menu")[0].getBoundingClientRect().left - 
		document.getElementsByTagName("h1")[0].getBoundingClientRect().right) / 2 - 
		document.getElementsByClassName("hello")[0].offsetWidth / 2 + 
		document.getElementsByTagName("h1")[0].getBoundingClientRect().right + "px";
	// show dropdown on menu click
	document.getElementsByClassName("menu")[0].addEventListener("click", function () {
		document.getElementsByClassName("dropdown")[0].classList.toggle("display-dropdown");
		document.getElementsByTagName("main")[0].addEventListener("click", function menuHide() {
			document.getElementsByClassName("dropdown")[0].classList.remove("display-dropdown");
			document.getElementsByTagName("main")[0].removeEventListener("click", menuHide);
		});
	});
	// position footer correctly
	window.addEventListener("DOMContentLoaded", function() {
		document.getElementsByTagName("main")[0].style.minHeight =
			document.documentElement.clientHeight - 80 + "px";
	});
</script>