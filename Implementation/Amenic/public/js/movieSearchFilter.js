/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

const elements = [
	"timeDropdownItem",
	"cinemaDropdownItem",
	"countryDropdownItem",
	"cityDropdownItem"
];

const setListeners = () => {
	elements.forEach((element) => {
		let docEl = document.getElementsByClassName(element);

		for (let item of docEl) {
			item.addEventListener("click", (e) => {
				e.preventDefault();
				document.getElementById(getElName(element)).innerHTML =
					e.target.innerHTML;
			});
		}
	});
};

const getElName = (className) => {
	switch (className) {
		case "timeDropdownItem":
			return "timeSelect";
		case "cinemaDropdownItem":
			return "cinemaSelect";
		case "countryDropdownItem":
			return "countrySelect";
		case "cityDropdownItem":
			return "citySelect";
		default:
			return error;
	}
};

setListeners();
