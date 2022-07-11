<?php
session_start();
// Check if user doesn't exist
include "../encrypt.php";
if (isset($_SESSION["securityPassword"])) {
	[$name, $password] = decode($_SESSION["securityPassword"]);
} else if (isset($_COOKIE["securityPassword"])) {
	[$name, $password] = decode($_COOKIE["securityPassword"]);
} else {
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
			exit;
		}
	} else {
		exit;
	}
} else {
	exit;
}
//check if user isn't in a group
if (isset($_SESSION["groupCode"])) {
	[$groupName, $groupPassword] = decodeGroup($_SESSION["groupCode"]);
} else if (isset($_COOKIE["groupCode"])) {
	[$groupName, $groupPassword] = decodeGroup($_COOKIE["groupCode"]);
} else {
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
			exit;
		}
	} else {
		exit;
	}
} else {
	exit;
}
//echo comments
function filterComments($arr) {
	global $groupName;
	return $arr["groupName"] == $groupName;
}
$commentsConn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
$stmt = mysqli_prepare($commentsConn, "SELECT * FROM epiz_31976759_OurCommunity.Comments where groupName = ?");
mysqli_stmt_bind_param($stmt, "s", $groupName);
mysqli_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
if ($comments) {
    $maxCommentsNum = 50;
    if (count(array_filter($comments, "filterComments")) > $maxCommentsNum) {
        $extraCommentId = array_filter($comments, "filterComments")[0]["id"];
        mysqli_query($commentsConn, "DELETE FROM epiz_31976759_OurCommunity.Comments WHERE ID = $extraCommentId");
    }
	uasort($comments, function ($a, $b) {
		return (($a["id"] < $b["id"]) ? 1 : -1);
	});
    echo json_encode(array_values($comments));
} else {
    echo "[]";
}

?>