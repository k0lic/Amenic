/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

const cityColumn = document.getElementById("cityDropdown");
const countryColumn = document.getElementById("countryDropdown");
const cinemaColumn = document.getElementById("cinemaDropdown");
const timeColumn = document.getElementById("timeDropdown");

let gracePeriod = null;

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

let results = [];

const updateTimes = () => {
	timeColumn.textContent = "";
	results.splice(0, results.length);

	getTimes().then((data) => {
		let timesSet = new Set();

		if (!Array.isArray(data)) {
			results.push(data["1"]);
		} else {
			results = data;
		}
		results.forEach(async (time) => {
			let re = /(.*) (.*):.*/;
			let extrTime = re.exec(time.dateTime)[2];
			re.lastIndex = 0;

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

	clearTimeout(gracePeriod);
	gracePeriod = setTimeout(() => {
		renderTable();
	}, 300);
	//renderTable();
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

	clearTimeout(gracePeriod);
	gracePeriod = setTimeout(() => {
		renderTable();
	}, 300);
	//renderTable();
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

	renderTable();
};
