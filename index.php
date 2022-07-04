<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>OurCommunity</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="author" content="Ezzeldien Rashad" />
	<meta name="description" content="OurCommunity, a community for meeting friends, sending messages, playing, etc....">
	<meta name="keywords" content="community, chat, message friends, meeting, main page, playing games" />
	<script type="text/javascript" src="scripts/index.js" defer></script>
	<script src="https://kit.fontawesome.com/5cf0e9fc67.js" crossorigin="anonymous"></script>
	<link rel="icon" href="pictures/community_logo.webp">
	<link rel="stylesheet" href="styles/index.css" />
	<link rel="stylesheet" href="styles/header-footer.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lusitana&display=swap" rel="stylesheet">
</head>
<body>
<?php 
date_default_timezone_set("Africa/Cairo");
include "header.php"; 
//add commenting functionality
if (isset($_POST["comment"])) {
	$commentsConn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$commentText = $_POST["commentText"] != "" ? $_POST["commentText"] : "|";
	$date = date("Y:m:d H:i:s");
	mysqli_query($commentsConn, "INSERT INTO Comments (name, body, date, lovers) VALUES ('$name', '$commentText', '$date', '[]')");	
}
//add delete comments functionality
if (isset($_GET["deleteCommentId"])) {
	mysqli_query($conn, "DELETE FROM Comments WHERE name = '" . $name . "' and id = '" . $_GET["deleteCommentId"] . "'");
}
// add love comments functionality
if (isset($_GET["loveCommentId"])) {
	$loversResult = mysqli_query($conn, "SELECT lovers FROM Comments where id = '" . $_GET["loveCommentId"] . "' and name != '" . $name . "'");
	$loversInfo = mysqli_fetch_assoc($loversResult);
	if ($loversInfo) {
		$lovers = json_decode($loversInfo["lovers"]);
		if (($key = array_search($name, $lovers)) !== false) {
			unset($lovers[$key]);
		} else {
			array_push($lovers, $name);
		}
		$newLovers = json_encode(array_values($lovers));
		mysqli_query($conn, "UPDATE Comments SET lovers = '$newLovers' WHERE id = " . $_GET["loveCommentId"]);
	}
}
?>
<main>

<button class="add-comment">+ add comment</button>
<form class="new-comment" aria-hidden="true" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
	<textarea name="commentText"></textarea>
	<input class="add-comment-btn" type="submit" name="comment" value="+add" />
</form>
<div class="comments-cont"></div>
<button class="more-comments">load more comments</button>

</main>
<footer>
<a href="signup.php">sign up</a>
<a href="login.php">log in</a>
<br /><br />
&copy; Ezzeldien 2022 - <?php echo date("Y") ?>
</footer>
</body>
</html>