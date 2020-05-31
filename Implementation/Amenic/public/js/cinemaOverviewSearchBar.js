/*
    Author: Andrija Kolić
    Github: k0lic
*/

const actualContent = document.getElementById("actualContent");
const searchBar = document.getElementById("searchBar");

var daysOfTheWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

// EVENT LISTENERS //

function searchForMoviesLike() {
	let searchTerm = searchBar.value;
	getMovieSearchResults(searchTerm).then((data) => {
		populateWithMovies(data);
	});
}

function searchForComingSoonsLike() {
	let searchTerm = searchBar.value;
	getSoonSearchResults(searchTerm).then((data) => {
		populateWithComingSoons(data);
	});
}

function searchForRoomsLike() {
	let searchTerm = searchBar.value;
	getRoomSearchResults(searchTerm).then((data) => {
		populateWithRooms(data);
	});
}

function searchForEmployeesLike() {
	let searchTerm = searchBar.value;
	getEmployeeSearchResults(searchTerm).then((data) => {
		populateWithEmployees(data);
	});
}

// DOM MODIFIERS //

function populateWithMovies(movies) {
	let content = "";
	movies.forEach((movie) => {
		if (movie.projection.canceled == 0) {
			content += movieTemplate(movie);
		} else {
			content += movieCanceledTemplate(movie);
		}
	});
	actualContent.innerHTML = content;
}

function populateWithComingSoons(soons) {
	let content = "";
	soons.forEach((soon) => {
		content += comingSoonTemplate(soon);
	});
	actualContent.innerHTML = content;
}

function populateWithRooms(rooms) {
	let content = "";
	rooms.forEach((room) => {
		content += roomTemplate(room);
	});
	actualContent.innerHTML = content;
}

function populateWithEmployees(employees) {
	let content = "";
	employees.forEach((employee) => {
		content += employeeTemplate(employee);
	});
	actualContent.innerHTML = content;
}

// DOM TEMPLATES //

function movieTemplate(movie) {
	let dateTime = new Date(movie.projection.dateTime);
	let dayOfTheWeek = daysOfTheWeek[dateTime.getDay()];
	let hours = dateTime.getHours();
	if (hours < 10) hours = "0" + hours;
	let minutes = dateTime.getMinutes();
	if (minutes < 10) minutes = "0" + minutes;
	return (
		'<a class="coolLink" href="/Cinema/EditMovie/' +
		movie.projection.idPro +
		'">' +
		'<div class="movieImgExtended centerY column">' +
		'<img src="' +
		movie.poster +
		'" class="movieImg" />' +
		'<div class="movieImgText row w80 mt-1 spaceBetween">' +
		"<div>" +
		movie.projection.roomName +
		"</div>" +
		"<div>" +
		dayOfTheWeek +
		" " +
		hours +
		":" +
		minutes +
		"</div>" +
		"</div>" +
		"</div>" +
		"</a>"
	);
}

function movieCanceledTemplate(movie) {
	let dateTime = new Date(movie.projection.dateTime);
	let dayOfTheWeek = daysOfTheWeek[dateTime.getDay()];
	let hours = dateTime.getHours();
	if (hours < 10) hours = "0" + hours;
	let minutes = dateTime.getMinutes();
	if (minutes < 10) minutes = "0" + minutes;
	return (
		'<div class="movieImgExtended centerY column">' +
		'<div class="movieCanceled centerY column">' +
		'<img src ="' +
		movie.poster +
		'" class="movieImg" />' +
		'<div class="movieImgText row w80 mt-1 spaceBetween">' +
		"<div > " +
		movie.projection.roomName +
		"</div>" +
		"<div>" +
		dayOfTheWeek +
		" " +
		hours +
		":" +
		minutes +
		"</div>" +
		"</div>" +
		"</div>" +
		'<div class="movieCanceledText">Canceled</div>' +
		"</div>"
	);
}

function comingSoonTemplate(soon) {
	return (
		'<a class="coolLink" href="/Cinema/EditComingSoon/' +
		soon.soon.tmdbID +
		'">' +
		'<div class="comingSoonExtended">' +
		'<img src="' +
		soon.poster +
		'" class="movieImg" />' +
		"</div>" +
		"</a>"
	);
}

function roomTemplate(room) {
	return (
		'<a class="coolLink" href="/Cinema/EditRoom/' +
		room.name +
		'"</a>' +
		'<div class="movieImgExtended centerY column">' +
		'<img src="/assets/Cinema/room.jpg" class="movieImg" />' +
		'<div class="movieImgText row w80 mt-1 centerRow">' +
		"<div>" +
		room.name +
		"</div>" +
		"</div>" +
		"</div>" +
		"</a>"
	);
}

function employeeTemplate(employee) {
	return (
		'<div class="rowWrapper">' +
		'<div class="userPicture">' +
		'<img src="' +
		(employee.image == null
			? "/assets/profPic.png"
			: "data:image/jpg;base64, " + employee.image) +
		'" alt="Worker pic" />' +
		"</div>" +
		'<div class="description">' +
		"<div><h1>" +
		employee.worker.firstName +
		" " +
		employee.worker.lastName +
		"</h1></div>" +
		"<div><span>" +
		employee.worker.email +
		"</span></div>" +
		"</div>" +
		'<div class="binWrapper">' +
		'<button type="button"' +
		"onClick=\"document.getElementById('workerForDelete').value='" +
		employee.worker.email +
		"'; areYouSure('You are about to remove an employee.','/Cinema/ActionRemoveEmployee')\"" +
		'class="highlightSvgOnHover">' +
		'<svg viewBox="-286 137 346.8 427">' +
		'<path d="M-53.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189C-43.6,296.2-48.1,291.7-53.6,291.7 z"/>' +
		'<path d="M-171.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-161.6,296.2-166.1,291.7-171.6,291.7z"/>' +
		'<path d="M-257.6,264.1v246.4c0,14.6,5.3,28.2,14.7,38.1c9.3,9.8,22.2,15.4,35.7,15.4H-18c13.5,0,26.4-5.6,35.7-15.4 c9.3-9.8,14.7-23.5,14.7-38.1V264.1c18.5-4.9,30.6-22.8,28.1-41.9C58,203.2,41.8,189,22.6,189h-51.2v-12.5 c0.1-10.5-4.1-20.6-11.5-28c-7.4-7.4-17.6-11.6-28.1-11.5H-157c-10.5-0.1-20.6,4-28.1,11.5c-7.4,7.4-11.6,17.5-11.5,28V189h-51.2 c-19.2,0-35.4,14.2-37.9,33.3C-288.2,241.3-276.1,259.2-257.6,264.1z M-18,544h-189.2c-17.1,0-30.4-14.7-30.4-33.5V265h250v245.5 C12.4,529.3-0.9,544-18,544z M-176.6,176.5c-0.1-5.2,2-10.2,5.7-13.9c3.7-3.7,8.7-5.7,13.9-5.6h88.8c5.2-0.1,10.2,1.9,13.9,5.6 c3.7,3.7,5.7,8.7,5.7,13.9V189h-128V176.5z M-247.8,209H22.6c9.9,0,18,8.1,18,18s-8.1,18-18,18h-270.4c-9.9,0-18-8.1-18-18 S-257.7,209-247.8,209z"/>' +
		'<path d="M-112.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-102.6,296.2-107.1,291.7-112.6,291.7z"/>' +
		"</svg>" +
		"</button>" +
		"</div>" +
		"</div>"
	);
}

// FETCH METHODS //

const getMovieSearchResults = async function (match) {
	let response = await fetch(
		"http://localhost:8080/Cinema/getMyProjectionsLike?match=" + match,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getSoonSearchResults = async function (match) {
	let response = await fetch(
		"http://localhost:8080/Cinema/getMyComingSoonsLike?match=" + match,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getRoomSearchResults = async function (match) {
	let response = await fetch(
		"http://localhost:8080/Cinema/getMyRoomsLike?match=" + match,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getEmployeeSearchResults = async function (match) {
	let response = await fetch(
		"http://localhost:8080/Cinema/getMyEmployeesLike?match=" + match,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	//console.log(data);
	return data;
};
