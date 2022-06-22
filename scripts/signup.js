// Check password strength
document.forms[0].password.addEventListener("input", function () {
	let password = document.forms[0].password.value;
	let passStrengthInfo = document.getElementById("passStrengthInfo");
	let strength = [/[a-z]/.test(password), /[A-Z]/.test(password),
		/\d/.test(password), password.length >= 8,
		/[-!@#$%^&*\(\)_=+`~.>,<\/?'";:\\|]/.test(password)]
		.reduce((sum, num) => sum + num, 0);
	switch (strength) {
		case 0:
		case 1:
			passStrengthInfo.innerHTML = "*very weak password";
			passStrengthInfo.style.color = "red";
			break;
		case 2:
			passStrengthInfo.innerHTML = "*weak password";
			passStrengthInfo.style.color = "deeppink";
			break;
		case 3:
			passStrengthInfo.innerHTML = "*good password";
			passStrengthInfo.style.color = "orange";
			break;
		case 4:
			passStrengthInfo.innerHTML = "*very good password";
			passStrengthInfo.style.color = "lightgreen";
			break;
		case 5:
			passStrengthInfo.innerHTML = "*excellent password"
			passStrengthInfo.style.color = "green";
			break;
	}
})

// Delete errors when user begins writing
document.forms[0].email.addEventListener("input", function () {
	document.getElementById("emailErr").innerHTML = "";
});

document.forms[0].name.addEventListener("input", function () {
	document.getElementById("nameErr").innerHTML = "";
});
