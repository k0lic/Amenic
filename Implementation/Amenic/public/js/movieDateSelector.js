/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

let relativeDate = new Date();

const updateDates = () => {
	updateDate("movieDateO1", -2, relativeDate);
	updateDate("movieDateI1", -1, relativeDate);

	updateDate("movieDate", 0, relativeDate);

	updateDate("movieDateI2", 1, relativeDate);
	updateDate("movieDateO2", 2, relativeDate);
};

const updateDate = (element, offset, relativeDate) => {
	let el = document.getElementById(element);
	let children = el.children;

	//let newDate = new Date();
	let newDate = new Date(
		relativeDate.getFullYear(),
		relativeDate.getMonth(),
		relativeDate.getDate() + offset
	);

	children[0].innerHTML = monthToStr(newDate.getMonth());
	children[1].innerHTML = newDate.getDate();
	children[2].innerHTML = dayToStr(newDate.getDay());
};

document.getElementById("movieArrowLeft").addEventListener("click", (e) => {
	relativeDate.setDate(relativeDate.getDate() - 1);
	updateDates();
});

document.getElementById("movieArrowRight").addEventListener("click", (e) => {
	relativeDate.setDate(relativeDate.getDate() + 1);
	updateDates();
});

const monthToStr = (month) => {
	switch (month) {
		case 0:
			return "Jan";
		case 1:
			return "Feb";
		case 2:
			return "Mar";
		case 3:
			return "Apr";
		case 4:
			return "May";
		case 5:
			return "Jun";
		case 6:
			return "Jul";
		case 7:
			return "Aug";
		case 8:
			return "Sep";
		case 9:
			return "Oct";
		case 10:
			return "Nov";
		case 11:
			return "Dec";
		default:
			return "error";
	}
};

const dayToStr = (day) => {
	switch (day) {
		case 0:
			return "Mon";
		case 1:
			return "Tue";
		case 2:
			return "Wed";
		case 3:
			return "Thu";
		case 4:
			return "Fri";
		case 5:
			return "Sat";
		case 6:
			return "Sun";
		default:
			return "error";
	}
};

updateDates();
