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
//check if user isn't in a group
if (isset($_SESSION["groupCode"])) {
	[$groupName, $groupPassword] = decodeGroup($_SESSION["groupCode"]);
} else if (isset($_COOKIE["groupCode"])) {
	[$groupName, $groupPassword] = decodeGroup($_COOKIE["groupCode"]);
} else {
	header("Location: groups.php");
	exit;
}
if (isset($groupName) && isset($groupPassword)) {
	$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$stmt = mysqli_prepare($conn, "SELECT groupPassword FROM epiz_31976759_OurCommunity.Groups WHERE groupName = ?");
	mysqli_stmt_bind_param($stmt, "s", $groupName);
	mysqli_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);	
	$info = mysqli_fetch_assoc($result);
	if ($info) {
		mysqli_stmt_free_result($stmt);
		if ($info["groupPassword"] != $groupPassword) {
			header("Location: groups.php");
			exit;
		}
	} else {
		header("Location: groups.php");
		exit;
	}
} else {
	header("Location: groups.php");
	exit;
}
//add commenting functionality
if (isset($_POST["comment"])) {
	$commentsConn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$commentText = $_POST["commentText"] != "" ? $_POST["commentText"] : "\r\n";
	$date = date("Y:m:d H:i:s");
	$commentStmt = mysqli_prepare($commentsConn, "INSERT INTO epiz_31976759_OurCommunity.Comments (name, body, date, lovers, comments, groupName) VALUES (?, ?, ?, '[]', '[]', ?)");
	mysqli_stmt_bind_param($commentStmt, "ssss", $name, $commentText, $date, $groupName);
	mysqli_execute($commentStmt);
}
//add delete comments functionality
if (isset($_GET["deleteCommentId"])) {
	$delStmt = mysqli_prepare($conn, "DELETE FROM epiz_31976759_OurCommunity.Comments WHERE name = '" . $name . "' and id = ?");
	mysqli_stmt_bind_param($delStmt, "i", $_GET["deleteCommentId"]);
	mysqli_execute($delStmt);

}
// add love comments functionality
if (isset($_GET["loveCommentId"])) {
	$loversResultStmt = mysqli_prepare($conn, "SELECT lovers FROM epiz_31976759_OurCommunity.Comments where id = ? and name != '" . $name . "'");
	mysqli_stmt_bind_param($loversResultStmt, "i", $_GET["loveCommentId"]);
	mysqli_execute($loversResultStmt);
	$loversResult = mysqli_stmt_get_result($loversResultStmt);
	$loversInfo = mysqli_fetch_assoc($loversResult);
	if ($loversInfo) {
		$lovers = json_decode($loversInfo["lovers"]);
		if (($key = array_search($name, $lovers)) !== false) {
			unset($lovers[$key]);
		} else {
			array_push($lovers, $name);
		}
		$newLovers = json_encode(array_values($lovers));
		$loversStmt = mysqli_prepare($conn, "UPDATE epiz_31976759_OurCommunity.Comments SET lovers = '$newLovers' WHERE id = ?");
		mysqli_stmt_bind_param($loversStmt, "i", $_GET["loveCommentId"]);
		mysqli_execute($loversStmt);
	}
}
//add comment to comment functionality
if (isset($_POST["c2cSubmit"]) && isset($_GET["c2cId"])) {
	$c2cStmt = mysqli_prepare($conn, "SELECT comments FROM epiz_31976759_OurCommunity.Comments WHERE id = ?");
	mysqli_stmt_bind_param($c2cStmt, "i", $_GET["c2cId"]);
	mysqli_execute($c2cStmt);
	$c2cResult = mysqli_stmt_get_result($c2cStmt);
	$c2cs = mysqli_fetch_assoc($c2cResult);
	if ($c2cs) {
		$c2cs = json_decode(str_replace("\r\n", "\\r\\n", $c2cs["comments"]));
		array_push($c2cs, "$name:" . $_POST["c2cContent"]);
		$newC2cs = json_encode(array_values($c2cs));
		$stmt = mysqli_prepare($conn, "UPDATE epiz_31976759_OurCommunity.Comments SET comments = ? WHERE id = ?");
		mysqli_stmt_bind_param($stmt, "si", $newC2cs, $_GET["c2cId"]);
		mysqli_execute($stmt);
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