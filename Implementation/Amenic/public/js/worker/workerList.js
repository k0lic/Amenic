/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

const list = document.getElementById("actualContent");

const searchBar = document.getElementById("searchBar");

const checkSVG =
	'<svg viewBox="79 -77.3 417 323.8"> <path d="M238.7,240.3c-4,4-9.4,6.2-15.1,6.2s-11.1-2.2-15.1-6.2L88.4,120c-12.5-12.5-12.5-32.7,0-45.2l15.1-15.1c12.5-12.5,32.7-12.5,45.2,0l75.1,75.1L426.4-67.9c12.5-12.5,32.7-12.5,45.2,0l15.1,15.1c12.5,12.5,12.5,32.7,0,45.2L238.7,240.3z"></path></svg>';

const binSVG =
	'<svg viewBox="-286 137 346.8 427"><path d="M-53.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189C-43.6,296.2-48.1,291.7-53.6,291.7 z"></path><path d="M-171.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-161.6,296.2-166.1,291.7-171.6,291.7z"></path><path d="M-257.6,264.1v246.4c0,14.6,5.3,28.2,14.7,38.1c9.3,9.8,22.2,15.4,35.7,15.4H-18c13.5,0,26.4-5.6,35.7-15.4 c9.3-9.8,14.7-23.5,14.7-38.1V264.1c18.5-4.9,30.6-22.8,28.1-41.9C58,203.2,41.8,189,22.6,189h-51.2v-12.5 c0.1-10.5-4.1-20.6-11.5-28c-7.4-7.4-17.6-11.6-28.1-11.5H-157c-10.5-0.1-20.6,4-28.1,11.5c-7.4,7.4-11.6,17.5-11.5,28V189h-51.2 c-19.2,0-35.4,14.2-37.9,33.3C-288.2,241.3-276.1,259.2-257.6,264.1z M-18,544h-189.2c-17.1,0-30.4-14.7-30.4-33.5V265h250v245.5 C12.4,529.3-0.9,544-18,544z M-176.6,176.5c-0.1-5.2,2-10.2,5.7-13.9c3.7-3.7,8.7-5.7,13.9-5.6h88.8c5.2-0.1,10.2,1.9,13.9,5.6 c3.7,3.7,5.7,8.7,5.7,13.9V189h-128V176.5z M-247.8,209H22.6c9.9,0,18,8.1,18,18s-8.1,18-18,18h-270.4c-9.9,0-18-8.1-18-18 S-257.7,209-247.8,209z"></path><path d="M-112.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-102.6,296.2-107.1,291.7-112.6,291.7z"></path></svg>';

let cinemaEmail = document.getElementById("cinemaEmail").value;

let rowNumber = 1;
let phrase = "";

const getReservations = async () => {
	let response = await fetch(
		`http://localhost:8080/worker/getReservations?cinemaEmail=${cinemaEmail}&phrase=${phrase}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getSeats = async (idRes) => {
	let response = await fetch(
		`http://localhost:8080/worker/getSeats?idRes=${idRes}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getUser = async (userEmail) => {
	let response = await fetch(
		`http://localhost:8080/worker/getUser?userEmail=${userEmail}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getProjection = async (idPro) => {
	let response = await fetch(
		`http://localhost:8080/worker/getProjection?idPro=${idPro}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getMovie = async (tmdbID) => {
	let response = await fetch(
		`http://localhost:8080/worker/getMovie?tmdbID=${tmdbID}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getTechnology = async (idTech) => {
	let response = await fetch(
		`http://localhost:8080/worker/getTechnology?idTech=${idTech}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const createConfirm = () => {
	let el = document.createElement("div");
	el.innerHTML = "Confirmed :)";
	el.classList.add("confirmedText");
	return el;
};

const createRow = (
	name,
	resID,
	total,
	room,
	seats,
	time,
	date,
	movieName,
	movieTech,
	confirmed
) => {
	const wrapper = document.createElement("div");

	wrapper.classList.add("rowWrapper");

	let el = document.createElement("div");
	el.classList.add("userPicture");
	let img = document.createElement("img");
	img.src = "/assets/profPic.png";
	img.alt = "Worker profile image";

	el.appendChild(img);
	wrapper.appendChild(el);

	el = document.createElement("div");
	el.classList.add("description");
	let row = document.createElement("div");
	row.classList.add("row");
	let h1 = document.createElement("h1");
	h1.innerHTML = `${name} · ID${resID} · €${total}`;
	row.appendChild(h1);
	el.appendChild(row);

	// First span row //
	row = document.createElement("div");
	row.classList.add("row", "mt-1");
	let spanWrapper = document.createElement("span");

	let movieNameSpan = document.createElement("span");
	movieNameSpan.id = `${rowNumber}movieNameSpan`;
	movieNameSpan.innerHTML = `${movieName} ·`;

	let techNameSpan = document.createElement("span");
	techNameSpan.id = `${rowNumber}techNameSpan`;
	techNameSpan.innerHTML = ` ${movieTech} ·`;

	let roomNameSpan = document.createElement("span");
	roomNameSpan.innerHTML = ` ${room} ·`;

	let seatsSpan = document.createElement("span");
	seatsSpan.innerHTML = ` ${seats}`;

	spanWrapper.appendChild(movieNameSpan);
	spanWrapper.appendChild(techNameSpan);
	spanWrapper.appendChild(roomNameSpan);
	spanWrapper.appendChild(seatsSpan);

	row.appendChild(spanWrapper);
	el.appendChild(row);

	row = document.createElement("div");
	row.classList.add("row", "mt-1");
	spanWrapper = document.createElement("span");

	let timeSpan = document.createElement("span");
	timeSpan.innerHTML = `${time} ·`;

	let dateSpan = document.createElement("span");
	dateSpan.innerHTML = ` ${date}`;

	spanWrapper.appendChild(timeSpan);
	spanWrapper.appendChild(dateSpan);

	row.appendChild(spanWrapper);

	el.appendChild(row);
	wrapper.appendChild(el);

	// end of description //

	let checkWrapper = document.createElement("div");
	checkWrapper.classList.add("checkWrapper");
	checkWrapper.id = `${resID}checkWrapper`;
	if (confirmed == 0) {
		let checkButton = document.createElement("button");
		checkButton.classList.add("highlightSvgOnHover");
		checkButton.type = "button";

		checkButton.setAttribute("onclick", `initiateConfirm(${resID})`);

		checkButton.innerHTML = checkSVG;

		checkWrapper.appendChild(checkButton);
	} else {
		checkWrapper.appendChild(createConfirm());
	}

	wrapper.appendChild(checkWrapper);

	let binWrapper = document.createElement("div");
	binWrapper.classList.add("binWrapper");
	let binButton = document.createElement("button");
	binButton.classList.add("highlightSvgOnHover");
	binButton.type = "button";
	binButton.setAttribute("onclick", `initiateDelete(${resID})`);
	binButton.innerHTML = binSVG;

	binWrapper.appendChild(binButton);

	wrapper.appendChild(binWrapper);
	rowNumber++;

	return wrapper;
};

const rowToStr = (row) => {
	return String.fromCharCode(65 + Number(row) - 1);
};

const convertSeats = (seatsArr) => {
	let seatsStr = "";

	if (Array.isArray(seatsArr)) {
		seatsArr.forEach((seat, indx, seatsArr) => {
			let row = rowToStr(seat.rowNumber);
			let col = seat.seatNumber;
			seatsStr += `${row}${col}`;
			if (indx < seatsArr.length - 1) seatsStr += ", ";
		});
	} else {
		let row = rowToStr(seatsArr["1"].rowNumber);
		let col = seatsArr["1"].seatNumber;
		seatsStr += `${row}${col}`;
	}

	return seatsStr;
};

const getTime = (str) => {
	let re = / (.*):(.*):(.*)/;

	let res = re.exec(str);

	return `${res[1]}:${res[2]}`;
};

const getDate = (str) => {
	let re = /(.*)-(.*)-(.*) /;

	let res = re.exec(str);

	let year = res[1];
	let month = res[2];
	let day = res[3];

	return `${day}.${month}.${year}.`;
};

let finishedRender = true;

const markFinished = () => {
	finishedRender = true;
};

let processed = 0;

let myRes = [];

const renderReservations = () => {
	phrase = searchBar.value;
	getReservations().then((reservations) => {
		list.textContent = "";
		processed = 0;
		myRes.splice(0, myRes.length);
		reservations.forEach(async (reservation, indx, arr) => {
			//let user = await getUser(reservation.email);
			let projection = await getProjection(reservation.idPro);
			let seats = await getSeats(reservation.idRes);

			let movie = await getMovie(projection.tmdbID);
			let technology = await getTechnology(projection.idTech);

			let name = `${reservation.firstName} ${reservation.lastName}`;

			let price = 0;
			if (Array.isArray(seats)) {
				price = seats.length * projection.price;
			} else {
				price = projection.price;
			}
			myRes.push(
				createRow(
					name,
					reservation.idRes,
					price,
					projection.roomName,
					convertSeats(seats),
					getTime(projection.dateTime),
					getDate(projection.dateTime),
					movie.title,
					technology.name,
					reservation.confirmed
				)
			);

			processed++;

			if (processed === arr.length) {
				list.textContent = "";
				myRes.forEach((res) => {
					list.appendChild(res);
				});
			}
		});
	});
};

renderReservations();

const confirmReservation = async (idRes) => {
	let response = await fetch(
		`http://localhost:8080/worker/confirmReservation?idRes=${idRes}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const initiateConfirm = (idRes) => {
	confirmReservation(idRes).then((data) => {
		let el = document.getElementById(`${idRes}checkWrapper`);
		el.textContent = "";
		el.appendChild(createConfirm());
	});
};

const deleteReservation = async (idRes) => {
	let postData = {
		idRes: idRes
	};

	var fd = new FormData();
	for (var i in postData) {
		fd.append(i, postData[i]);
	}

	let response = await fetch(`http://localhost:8080/worker/deleteReservation`, {
		method: "POST",
		body: fd,
		mode: "cors"
	});

	let data = await response.json();
	return data;
};

const initiateDelete = (idRes) => {
	deleteReservation(idRes).then((data) => {
		renderReservations();
	});
};

searchBar.addEventListener("input", renderReservations);
