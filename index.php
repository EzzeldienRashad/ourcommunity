<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="description" content="a community for meeting friends, sending messages, chating, etc....">
	<meta name="keywords" content="community chat message friends meeting">
	<script type="text/javascript" src="scripts/index.js" defer></script>
	<script src="https://kit.fontawesome.com/5cf0e9fc67.js" crossorigin="anonymous"></script>
	<script src="arrays/comments.json?nocache=123" ></script>
	<link rel="icon" href="pictures/community_logo.webp">
	<link rel="stylesheet" href="styles/index.css" />
	<link rel="stylesheet" href="styles/header-footer.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet">
</head>
<body>
<?php 
include "header.php"; 
if (isset($_POST["comment"])) {
	$comments = json_decode(file_get_contents("arrays/comments.json"));
	$name = isset($_SESSION["securityPassword"]) ? decode($_SESSION["securityPassword"])[0] : "unknown user";
	$comments = array(array($name, $_POST["commentText"] != "" ? $_POST["commentText"] : "|"), ...array_slice($comments, 0, 5));
	print_r($comments);
}
?>
<main>

<button class="add-comment">+ add comment</button>
<form class="new-comment" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
	<textarea name="commentText"></textarea>
	<input class="add-comment-btn" type="submit" name="comment" value="+add" />
</form>
<div class="comments-cont"></div>

</main>
<footer>
<a href="signup.php">sign up</a>
<a href="login.php">log in</a>
<br /><br />
&copy; Ezzeldien 2022
</footer>
</body>
</html>