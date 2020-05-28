/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

const countryField = document.getElementById("countryId");

const countryDropdown = document.getElementById("countryDropdown");
const cityDropdown = document.getElementById("cityDropdown");

const getCountries = async () => {
	let response = await fetch(`http://localhost:8080/register/getCountries`, {
		method: "GET",
		mode: "cors"
	});

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
	countryDropdown.textContent = "";

	getCountries().then((countries) => {
		countries.forEach((country) => {
			let el = addCountry(country.idCountry, country.name);

			countryDropdown.appendChild(el);

			el.addEventListener("click", (e) => {
				e.preventDefault();

				countryField.value = countryDropdown.selectedIndex + 1;
				console.log(`Someone selected country ${countryField.value}!`);
				renderCities();
			});
		});
	});
};

const getCities = async () => {
	let response = await fetch(
		`http://localhost:8080/register/getCities?idCountry=${Number(
			countryField.value
		)}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();

	return data;
};

const renderCities = () => {
	cityDropdown.textContent = "";

	getCities().then((cities) => {
		cities.forEach((city) => {
			let el = addCity(city.idCity, city.name);

			cityDropdown.appendChild(el);

			el.addEventListener("click", (e) => {
				e.preventDefault();

				updateSelects("city");
			});
		});
	});
};

renderCountries();
renderCities();
