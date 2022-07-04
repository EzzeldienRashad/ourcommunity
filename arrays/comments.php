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
//echo comments
$commentsConn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
$result = mysqli_query($commentsConn, "SELECT * FROM Comments");
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
if ($comments) {
    $maxCommentsNum = 50;
    if (count($comments) > $maxCommentsNum) {
        $leastId = array_slice($comments, -$maxCommentsNum)[0]["id"];
        mysqli_query($commentsConn, "DELETE FROM Comments WHERE ID < $leastId");
    }
	uasort($comments, function ($a, $b) {
		return (($a["id"] < $b["id"]) ? 1 : -1);
	});
    echo json_encode(array_values($comments));
} else {
    echo "[]";
}

?>