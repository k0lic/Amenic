/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

let date = new Date();
let time = "";
let cinema = "";
let country = "1";
let city = "";
let tmdbID = document.getElementById("movieID").value;

// FILTER LOGIC //

const setDefaultCountry = () => {
	document.getElementById("countrySelect").innerHTML = "Serbia";
};

setDefaultCountry();

// DATE LOGIC //

const updateDates = () => {
	updateDate("movieDateO1", -2, date);
	updateDate("movieDateI1", -1, date);

	updateDate("movieDate", 0, date);

	updateDate("movieDateI2", 1, date);
	updateDate("movieDateO2", 2, date);
};

const updateDate = (element, offset, date) => {
	let el = document.getElementById(element);
	let children = el.children;

	//let newDate = new Date();
	let newDate = new Date(
		date.getFullYear(),
		date.getMonth(),
		date.getDate() + offset
	);

	children[0].innerHTML = monthToStr(newDate.getMonth());
	children[1].innerHTML = newDate.getDate();
	children[2].innerHTML = dayToStr(newDate.getDay());
};

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

// PROJECTION RENDERING LOGIC //

const table = document.getElementById("showingTable");

const prepareDate = () => {
	return `${date.getFullYear()}-${
		date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1
	}-${date.getDate() < 10 ? "0" + date.getDate() : date.getDate()}`;
};

const getProjections = async () => {
	let fixedDate = prepareDate();
	let response = await fetch(
		`http://localhost:8080/movie/getProjections?tmdbID=${tmdbID}&date=${fixedDate}&time=${time}&cinema=${cinema}&country=${country}&city=${city}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getCinemaName = async (email) => {
	let response = await fetch(
		`http://localhost:8080/movie/getCinemaName?email=${email}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data.name;
};

const getTechName = async (idTech) => {
	let response = await fetch(
		`http://localhost:8080/movie/getTechName?idTech=${idTech}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data.name;
};

const createRow = (cinemaName, time, room, type) => {
	let wrapper = document.createElement("div");

	wrapper.classList.add("showingTableRow", "row", "centerY", "mb-1");

	let el = document.createElement("div");
	el.classList.add("w10", "column", "centerRow");
	let img = document.createElement("img");
	img.classList.add("userIcon");
	img.src = "https://via.placeholder.com/150";

	el.appendChild(img);

	wrapper.appendChild(el);

	// Cinema
	el = document.createElement("div");
	el.classList.add("w30", "textCenter");
	el.innerHTML = cinemaName;
	wrapper.appendChild(el);

	// Time
	el = document.createElement("div");
	el.classList.add("w20", "textCenter");
	el.innerHTML = time;
	wrapper.appendChild(el);

	// Room
	el = document.createElement("div");
	el.classList.add("w20", "textCenter");
	el.innerHTML = room;
	wrapper.appendChild(el);

	// Type
	el = document.createElement("div");
	el.classList.add("w20", "textCenter");
	el.innerHTML = type;
	wrapper.appendChild(el);

	return wrapper;
};

const createHeader = () => {
	const wrapper = document.createElement("div");
	wrapper.classList.add("showingTableHeader", "row", "centerY");

	// Empty
	let el = document.createElement("div");
	el.classList.add("w10");
	wrapper.appendChild(el);

	// Cinema
	el = document.createElement("div");
	el.classList.add("w30", "textCenter");
	el.innerHTML = "Cinema";
	wrapper.appendChild(el);

	// Time
	el = document.createElement("div");
	el.classList.add("w20", "textCenter");
	el.innerHTML = "Time";
	wrapper.appendChild(el);

	// Room
	el = document.createElement("div");
	el.classList.add("w20", "textCenter");
	el.innerHTML = "Room";
	wrapper.appendChild(el);

	// Type
	el = document.createElement("div");
	el.classList.add("w20", "textCenter");
	el.innerHTML = "Type";
	wrapper.appendChild(el);

	table.appendChild(wrapper);
};

document.getElementById("movieArrowLeft").addEventListener("click", (e) => {
	e.preventDefault();
	indx -= 5;
	if (indx < 0) indx = 0;

	addToTable(indx);
});

document.getElementById("movieArrowRight").addEventListener("click", (e) => {
	e.preventDefault();
	indx += 5;

	addToTable(indx);
});

const showPagination = (showing) => {
	let el = document.getElementById("showingTablePagination");
	if (showing) {
		el.classList.add("showPagination");
	} else {
		el.classList.remove("showPagination");
	}
};

const renderSad = () => {
	const img = document.createElement("img");
	img.src = "/assets/Movie/popcornSad.svg";
	img.classList.add("popcornIcon", "mt-3");

	const txt = document.createElement("span");
	txt.classList.add("popcornText", "mt-1");
	txt.innerHTML = "No screenings found";

	table.appendChild(img);
	table.appendChild(txt);
};

let needPagination = false;
let projections = [];
let projectionsAdded = 0; // used for the callback
let indx = 0;

const addToTable = (indx) => {
	// Clear the table
	table.textContent = "";
	createHeader();

	let cnt = 0;
	for (let i = indx; i < projections.length && cnt < 5; i++) {
		table.appendChild(projections[i]);
		cnt++;
	}

	projectionsAdded = 0;
};
let resultsPL = [];

const renderTable = () => {
	// Clear the table
	table.textContent = "";

	needPagination = false;
	projectionsAdded = 0;
	indx = 0;
	projections.splice(0, projections.length);
	resultsPL.splice(0, resultsPL.length);

	//document.getElementById("paginationRow").textContent = "";
	showPagination(false);
	createHeader();

	getProjections().then((data) => {
		if (data.length > 5) needPagination = true;

		if (data.length < 1) {
			renderSad();
		} else {
			if (!Array.isArray(data)) {
				resultsPL.push(data["1"]);
			} else {
				resultsPL = data;
			}
			resultsPL.forEach(async (projection, index, data) => {
				let re = /(.*) (.*):.*/;

				projections.push(
					createRow(
						await getCinemaName(projection.email),
						re.exec(projection.dateTime)[2],
						projection.roomName,
						await getTechName(projection.idTech)
					)
				);
				projectionsAdded++;

				if (projectionsAdded == data.length) addToTable(indx);
			});

			if (needPagination) showPagination(true);
		}
	});
};

renderTable();
