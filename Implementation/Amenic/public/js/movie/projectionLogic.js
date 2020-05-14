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

const createPagination = () => {
	const wrapperInner = document.createElement("div");
	wrapperInner.classList.add("showingTablePagination");

	let el = document.createElement("div");
	el.classList.add("column", "centerRow", "showingTableArrow", "mr-3");
	let img = document.createElement("img");
	img.src = "/assets/Movie/arrowLeft.svg";
	img.classList.add("movieArrow");
	img.id = "movieArrowLeft";
	el.appendChild(img);

	wrapperInner.appendChild(el);

	el = document.createElement("div");
	el.classList.add("column", "centerRow", "showingTableArrow");
	img = document.createElement("img");
	img.src = "/assets/Movie/arrowRight.svg";
	img.classList.add("movieArrow");
	img.id = "movieArrowRight";
	el.appendChild(img);

	wrapperInner.appendChild(el);

	document.getElementById("paginationRow").appendChild(wrapperInner);
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

const renderTable = () => {
	// Clear the table
	table.textContent = "";
	document.getElementById("paginationRow").textContent = "";
	createHeader();

	getProjections().then((data) => {
		if (data.length < 1) {
			renderSad();
		} else {
			data.forEach(async (projection) => {
				let re = /(.*) (.*):.*/g;
				table.appendChild(
					createRow(
						await getCinemaName(projection.email),
						re.exec(projection.dateTime)[2],
						projection.roomName,
						await getTechName(projection.idTech)
					)
				);
			});
			createPagination();
		}
	});
};

renderTable();
