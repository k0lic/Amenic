/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

const cityColumn = document.getElementById("cityDropdown");
const countryColumn = document.getElementById("countryDropdown");
const cinemaColumn = document.getElementById("cinemaDropdown");
const timeColumn = document.getElementById("timeDropdown");

const getCities = async () => {
	let response = await fetch(
		`http://localhost:8080/movie/getCities?country=${country}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getCountries = async () => {
	let response = await fetch(`http://localhost:8080/movie/getCountries`, {
		method: "GET",
		mode: "cors"
	});

	let data = await response.json();
	return data;
};

const getCinemas = async () => {
	let response = await fetch(
		`http://localhost:8080/movie/getCinemas?country=${country}&city=${city}&tmdbID=${tmdbID}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getTimes = async () => {
	let fixedDate = prepareDate();
	let response = await fetch(
		`http://localhost:8080/movie/getTimes?tmdbID=${tmdbID}&cinema=${cinema}&date=${fixedDate}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const createDropdownItem = (id, name, type) => {
	const el = document.createElement("li");
	el.classList.add(`${type}DropdownItem`);
	if (type != "time") el.id = id;
	el.innerHTML = name;

	return el;
};

const updateCountries = () => {
	countryColumn.textContent = "";

	getCountries().then((data) => {
		data.forEach(async (country) => {
			let el = createDropdownItem(country.idCountry, country.name, "country");
			countryColumn.appendChild(el);

			el.addEventListener("click", (e) => {
				e.preventDefault();
				document.getElementById("countrySelect").innerHTML = e.target.innerHTML;

				setFilterParam("countryDropdownItem", e.target.id);
			});
		});
	});
};

const updateCities = () => {
	cityColumn.textContent = "";

	getCities().then((data) => {
		data.forEach(async (city) => {
			let el = createDropdownItem(city.idCity, city.name, "city");
			cityColumn.appendChild(el);

			el.addEventListener("click", (e) => {
				e.preventDefault();
				document.getElementById("citySelect").innerHTML = e.target.innerHTML;

				setFilterParam("cityDropdownItem", e.target.id);
			});
		});
	});
};

const updateCinemas = () => {
	cinemaColumn.textContent = "";
	getCinemas().then((data) => {
		let cinemaSet = new Set();
		data.forEach(async (cinema) => {
			if (cinemaSet.has(cinema.email)) return;
			cinemaSet.add(cinema.email);

			let el = createDropdownItem(cinema.email, cinema.name, "cinema");
			cinemaColumn.appendChild(el);

			el.addEventListener("click", (e) => {
				e.preventDefault();
				document.getElementById("cinemaSelect").innerHTML = e.target.innerHTML;

				setFilterParam("cinemaDropdownItem", e.target.id);
			});
		});
		cinemaSet.clear();
		delete cinemaSet;
	});
};

const updateTimes = () => {
	timeColumn.textContent = "";

	getTimes().then((data) => {
		let re = /(.*) (.*):.*/;

		let timesSet = new Set();

		data.forEach(async (time) => {
			let extrTime = re.exec(time.dateTime)[2];

			if (timesSet.has(extrTime)) return;
			timesSet.add(extrTime);

			let el = createDropdownItem(0, extrTime, "time");

			timeColumn.appendChild(el);

			el.addEventListener("click", (e) => {
				e.preventDefault();
				document.getElementById("timeSelect").innerHTML = e.target.innerHTML;

				setFilterParam("timeDropdownItem", e.target.innerHTML);
			});
		});
		timesSet.clear();
		delete timesSet;
	});
};

updateCountries();
updateCities();
updateCinemas();
updateTimes();

const resetFilter = (filterName) => {
	document.getElementById(`${filterName}Select`).innerHTML = "Select";
};

document.getElementById("movieArrowLeft").addEventListener("click", (e) => {
	date.setDate(date.getDate() - 1);
	updateDates();

	time = "";
	cinema = "";
	city = "";

	resetFilter("time");
	resetFilter("cinema");
	resetFilter("city");

	updateCities();
	updateCinemas();
	updateTimes();

	renderTable();
});

document.getElementById("movieArrowRight").addEventListener("click", (e) => {
	date.setDate(date.getDate() + 1);
	updateDates();

	time = "";
	cinema = "";
	city = "";

	resetFilter("time");
	resetFilter("cinema");
	resetFilter("city");

	updateCities();
	updateCinemas();
	updateTimes();

	renderTable();
});

const setFilterParam = (className, value) => {
	switch (className) {
		case "timeDropdownItem":
			time = value + ":00";
			break;
		case "cinemaDropdownItem":
			cinema = value;
			time = "";
			resetFilter("time");
			updateTimes();
			break;
		case "countryDropdownItem":
			country = value;
			time = "";
			cinema = "";
			city = "";

			resetFilter("time");
			resetFilter("cinema");
			resetFilter("city");

			updateCities();
			updateCinemas();
			updateTimes();
			break;
		case "cityDropdownItem":
			city = value;
			cinema = "";
			time = "";

			resetFilter("time");
			resetFilter("cinema");

			updateCinemas();
			updateTimes();
			break;
		default:
			return error;
	}
	/*
	console.log("CINEMA " + cinema);
	console.log("TIME " + time);
	console.log("COUNTRY " + country);
	console.log("CITY " + city);
	*/
	renderTable();
};
