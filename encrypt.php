<?php 
function encode($name) {
	$conn = mysqli_connect("localhost", "epiz_31976759", "xhb1FTZFr4SdTM9", "epiz_31976759_OurCommunity");
	$result = mysqli_query($conn, "SELECT securityPassword FROM epiz_31976759_OurCommunity.Users where name = '" . $name . "'");
	$securityPassword = mysqli_fetch_assoc($result)["securityPassword"];
	mysqli_free_result($result);
	mysqli_close($conn);
	return base64_encode("alhHOY575FtuUT" . $name . "jjsljHIO89" . $securityPassword . "23rRrd");
}

function decode($str) {
	$str = base64_decode($str);
	return explode("jjsljHIO89", substr(explode("alhHOY575FtuUT", $str)[1], 0, -6));
}

function encodeGroup($groupName, $groupPassword) {
	return base64_encode("LHG77tfy78LFQZ567Ll" . $groupName . "hhu54IGGggI678" . $groupPassword . "ho75I");
}

function decodeGroup($str) {
	$str = base64_decode($str);
	return explode("hhu54IGGggI678", substr(explode("LHG77tfy78LFQZ567Ll", $str)[1], 0, -5));
}
?>