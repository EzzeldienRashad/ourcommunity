<?php function encode($name) {
	$conn = mysqli_connect("localhost", "OurCommunity", "1o2u3r4c5o@", "OurCommunity");
	$result = mysqli_query($conn, "SELECT securityPassword FROM users where name = '" . $name . "'");
	$securityPassword= mysqli_fetch_assoc($result)["securityPassword"];
	mysqli_free_result($result);
	mysqli_close($conn);
	return base64_encode("alhHOY575FtuUT" . $name . "jjsljHIO89" . $securityPassword . "23rRrd");
}

function decode($str) {
	$str = base64_decode($str);
	return array(explode("jjsljHIO89", explode("alhHOY575FtuUT", $str)[1])[0],
	explode("23rRrd", explode("jjsljHIO89", $str)[1])[0]);
}
?>