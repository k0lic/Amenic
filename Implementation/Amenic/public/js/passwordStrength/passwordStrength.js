/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

let password = document.getElementById("password");

password.addEventListener("input", () => {
	if (password.value.length < 6) {
		wipeClasses();
		return;
	}

	let result = zxcvbn(password.value);

	if (result.score > 0) {
		setBars(result.score);
	} else {
		wipeClasses();
	}
});

const wipeClasses = () => {
	for (let i = 1; i < 5; i++) {
		let bar = document.getElementById(`strengthBar${i}`);
		if (bar.classList.contains("strengthBarFilled")) {
			bar.classList.remove("strengthBarFilled");
		}
	}
};

const setBars = (strength) => {
	wipeClasses();

	for (let i = 1; i <= strength; i++) {
		document
			.getElementById(`strengthBar${i}`)
			.classList.add("strengthBarFilled");
	}
};
