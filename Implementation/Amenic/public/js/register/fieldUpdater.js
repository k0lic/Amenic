/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

const countryField = document.getElementById("countryId");

const countryDropdown = document.getElementById("countryDropdown");
const cityDropdown = document.getElementById("cityDropdown");

let currCountry = 1;

const getCountries = async () => {
	let response = await fetch(
		`http://` + window.location.host + `/register/getCountries`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();

	return data;
};

const addCountry = (idCountry, countryName) => {
	let opt = document.createElement("option");
	opt.value = idCountry;
	opt.innerHTML = countryName;

	return opt;
};

const addCity = (idCity, cityName) => {
	let opt = document.createElement("option");
	opt.value = idCity;
	opt.innerHTML = cityName;

	return opt;
};

const renderCountries = () => {
	document.getElementById("countryDropdown").textContent = "";

	getCountries().then((countries) => {
		countries.forEach((country) => {
			let el = addCountry(country.idCountry, country.name);

			document.getElementById("countryDropdown").appendChild(el);
		});
	});
};

const getCities = async () => {
	console.log(`Current country is ${currCountry}`);
	let response = await fetch(
		`http://` +
			window.location.host +
			`/register/getCities?idCountry=${Number(currCountry)}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();

	return data;
};

const renderCities = () => {
	document.getElementById("cityDropdown").textContent = "";

	getCities().then((cities) => {
		console.log(cities);
		cities.forEach((city) => {
			let el = addCity(city.idCity, city.name);

			document.getElementById("cityDropdown").appendChild(el);

			el.addEventListener("click", (e) => {
				e.preventDefault();
			});
		});
	});
};

renderCountries();
renderCities();

countryDropdown.addEventListener("change", () => {
	document.getElementById("countryId").value =
		document.getElementById("countryDropdown").selectedIndex + 1;
	currCountry = countryDropdown.selectedIndex + 1;
	renderCities();
});
