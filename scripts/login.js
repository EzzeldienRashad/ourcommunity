// Delete errors when user begins typing
document.forms[0].email.addEventListener("input", function () {
	document.getElementById("emailErr").innerHTML = "";
});
document.forms[0].password.addEventListener("input", function () {
	document.getElementById("passwordErr").innerHTML = "";
});
