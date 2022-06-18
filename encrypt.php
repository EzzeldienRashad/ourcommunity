<?php function encode($name) {
	$conn = mysqli_connect("localhost", "OurCommunity", "1o2u3r4c5o@", "OurCommunity");
	$result = mysqli_query($conn, "SELECT encPassword FROM users where name = '" . $name . "'");//To be named securityPassword in database
	$encPassword /*to be named securityPassword*/= mysqli_fetch_assoc($result)["encPassword"];
	mysqli_free_result($result);
	mysqli_close($conn);
	return base64_encode("alhHOY575FtuUT" . $name . "jjsljHIO89" . $encPassword . "23rRrd");
}

function decode($str) {
	$str = base64_decode($str);
	return array(explode("jjsljHIO89", explode("alhHOY575FtuUT", $str)[1])[0],
	explode("23rRrd", explode("jjsljHIO89", $str)[1])[0]);
}
?>