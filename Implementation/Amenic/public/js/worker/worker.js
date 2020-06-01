/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/
const dateDropdown = document.getElementById("date");
const startTimeDropdown = document.getElementById("startTime");
const roomDropdown = document.getElementById("rooms");
const techDropdown = document.getElementById("technology");
const movieDropdown = document.getElementById("movieName");
const confirmButton = document.getElementById("employeeSellConfirm");

const confirmError = document.getElementById("workerConfirmError");
const confirmSuccess = document.getElementById("workerConfirmSuccess");

const resRoom = document.getElementById("reservationRoom");

const rowsEl = document.getElementById("rows");
const colsEl = document.getElementById("cols");
const priceEl = document.getElementById("price");

let selectedSeats = new Set();
let total = 0;

let tmdbID = "";
let searchDate = "";
let searchTime = "";
let searchRoom = "";
let searchTech = "";
let searchPro = "";

let dateSet = new Set();
let timeSet = new Set();
let roomSet = new Set();
let techSet = new Set();

// INIT LISTENERS //
movieDropdown.addEventListener("change", () => {
	tmdbID = movieDropdown.value;
	updateDates();
});

dateDropdown.addEventListener("change", () => {
	searchDate = dateDropdown.value;
	updateTimes();
});

startTimeDropdown.addEventListener("change", () => {
	searchTime = startTimeDropdown.value;
	updateRooms();
});

roomDropdown.addEventListener("change", () => {
	searchRoom = roomDropdown.value;
	updateTech();
});

techDropdown.addEventListener("change", () => {
	searchTech = techDropdown.value;
	updateProjection();
});

const renderSeats = () => {
	let rows = rowsEl.value;
	let columns = colsEl.value;
	let seats = rows * columns;

	let target = document.getElementById("projectionSeating");
	target.textContent = "";

	target.style.display = "grid";
	target.style.gridTemplateColumns =
		"repeat(" + (Number(columns) + 1) + ", 1fr)";
	target.style.gridTemplateRows = "repeat(" + (Number(rows) + 1) + ", 1fr)";

	let cellSize = 50;
	let width = columns * cellSize;
	let height = rows * cellSize;

	target.style.width = width + "px";
	target.style.height = height + "px";

	let content = "";

	for (let i = 0; i < rows; i++) {
		let rowLabel = String.fromCharCode(65 + i);
		content += '<div class="projectionSeatLabels">' + rowLabel + "</div>";
		for (let j = 0; j < columns; j++) {
			content += `<div class="projectionSeat" id=${rowLabel}${j + 1}></div>`;
		}
	}

	content += "<div></div>";
	for (let j = 0; j < columns; j++) {
		let columnLabel = j + 1;
		content += '<div class="projectionSeatLabels">' + columnLabel + "</div>";
	}

	target.innerHTML = content;
};

//renderSeats();

const attachSeatListeners = () => {
	let els = document.getElementsByClassName("projectionSeat");
	for (let el of els) {
		el.addEventListener("click", (e) => {
			if (e.target.classList.contains("selectedProjectionSeat")) {
				// Remove the seat from the current selection
				removeSelected(e.target.id);
			} else {
				addSelected(e.target.id);
			}
		});
	}
};

const addSelected = (seat) => {
	if (!selectedSeats.has(seat)) {
		document.getElementById(seat).classList.add("selectedProjectionSeat");
		selectedSeats.add(seat);
		updateSelected();

		if (selectedSeats.size > 6) {
			showSeatError();
		}
		updateSeatsText();
		updatePrice(Number(priceEl.value));
	}
};

const removeSelected = (seat) => {
	if (selectedSeats.has(seat)) {
		document.getElementById(seat).classList.remove("selectedProjectionSeat");
		selectedSeats.delete(seat);
		updateSelected();
		updateSeatsText();
		if (selectedSeats.size < 7) {
			hideSeatError();
		}
		if (selectedSeats.size == 0) {
			disableReservationButton();
		}
		updatePrice(-Number(priceEl.value));
	}
};

const updateSelected = () => {
	// Clear the seats
	reservedSeats.textContent = "";

	selectedSeats.forEach((seat) => {
		let el = document.createElement("span");
		el.classList.add("reservedSeat");
		el.innerHTML = seat;

		reservedSeats.appendChild(el);
	});
};

const showSeatError = () => {
	document.getElementById("projectionError").classList.add("showModal");
	disableReservationButton();
};

const hideSeatError = () => {
	document.getElementById("projectionError").classList.remove("showModal");
	document
		.getElementById("employeeSellConfirm")
		.classList.remove("reservationButtonDisabled");
};

const disableReservationButton = () => {
	document
		.getElementById("employeeSellConfirm")
		.classList.add("reservationButtonDisabled");
};

const updateSeatsText = () => {
	let word = selectedSeats.size < 2 ? "seat" : "seats";

	if (selectedSeats.size == 0) {
		numSeats.innerHTML = "No seats selected";
		return;
	}

	numSeats.innerHTML = `${selectedSeats.size} ${word}`;
	if (selectedSeats.size < 7) {
		document
			.getElementById("employeeSellConfirm")
			.classList.remove("reservationButtonDisabled");
	}
};

disableReservationButton();
updateSeatsText();

const updatePrice = (offset) => {
	total += Number(offset);
	totalPrice.innerHTML = `€${total.toFixed(2)}`;
};

const resetPrice = () => {
	total = 0;
	totalPrice.innerHTML = `€${total.toFixed(2)}`;
};

updatePrice(0);

const getAllProjections = async () => {
	let response = await fetch(
		`http://` + window.location.host + `/worker/getAllProjections?cinemaEmail=${cinemaEmail}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getDates = async () => {
	let response = await fetch(
		`http://` + window.location.host + `/worker/getDates?tmdbID=${tmdbID}&cinemaEmail=${cinemaEmail}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getTimes = async () => {
	let response = await fetch(
		`http://` + window.location.host + `/worker/getTimes?tmdbID=${tmdbID}&cinemaEmail=${cinemaEmail}&date=${searchDate}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getRooms = async () => {
	let response = await fetch(
		`http://` + window.location.host + `/worker/getRooms?tmdbID=${tmdbID}&cinemaEmail=${cinemaEmail}&date=${searchDate}&time=${searchTime}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getTech = async () => {
	let response = await fetch(
		`http://` + window.location.host + `/worker/getTech?tmdbID=${tmdbID}&cinemaEmail=${cinemaEmail}&date=${searchDate}&time=${searchTime}&roomName=${searchRoom}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getSpecProjection = async () => {
	let response = await fetch(
		`http://` + window.location.host + `/worker/getSpecProjection?idPro=${searchPro}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const getDateDash = (str) => {
	let re = /(.*)-(.*)-(.*) /;

	let res = re.exec(str);

	let year = res[1];
	let month = res[2];
	let day = res[3];

	return `${day}-${month}-${year}`;
};

const createDropdownItem = (value, inner) => {
	const el = document.createElement("option");
	el.innerHTML = inner;
	el.value = value;

	return el;
};

let reservations = [];

const getNewSeats = async (idPro) => {
	let response = await fetch(
		`http://` + window.location.host + `/worker/getProjSeats?idPro=${idPro}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

const markUnavailable = (seat) => {
	document.getElementById(seat).classList.add("seatTaken");
};

const fillReserved = (idPro) => {
	reservations.splice(0, reservations);

	getNewSeats(idPro).then((data) => {
		if (!Array.isArray(data)) {
			reservations.push(data["1"]);
		} else {
			reservations = data;
		}

		reservations.forEach(async (reservation) => {
			let row = String.fromCharCode(65 + Number(reservation.rowNumber) - 1);
			let seat = reservation.seatNumber;
			markUnavailable(`${row}${seat}`);
		});
	});
};

let projectionSet = new Set();

const updateProjections = () => {
	projectionSet.clear();
	getAllProjections().then((projections) => {
		if (Array.isArray(projections)) {
			projections.forEach((projection) => {
				if (projectionSet.has(projection.tmdbID)) return;
				projectionSet.add(projection.tmdbID);

				movieDropdown.appendChild(
					createDropdownItem(projection.tmdbID, projection.title)
				);
			});
			tmdbID = projections[0].tmdbID;
		} else {
			movieDropdown.appendChild(
				createDropdownItem(projections["1"].tmdbID, projections["1"].title)
			);
			tmdbID = projections["1"].tmdbID;
		}

		updateDates();
	});
};

const updateDates = () => {
	dateSet.clear();
	dateDropdown.textContent = "";

	getDates().then((dates) => {
		if (Array.isArray(dates)) {
			dates.forEach((date) => {
				let newDate = getDateDash(date.dateTime);
				dateSet.add(newDate);
			});
			searchDate = getDateDash(dates[0].dateTime);
		} else {
			let dateN = getDateDash(dates["1"].dateTime);
			dateSet.add(dateN);
			searchDate = dateN;
		}

		dateSet.forEach((date) => {
			dateDropdown.appendChild(createDropdownItem(date, date));
		});

		updateTimes();
	});
};

const updateTimes = () => {
	startTimeDropdown.textContent = "";
	timeSet.clear();

	getTimes().then((times) => {
		if (Array.isArray(times)) {
			times.forEach((time) => {
				let tm = getTime(time.dateTime);
				timeSet.add(tm);
			});
			searchTime = getTime(times[0].dateTime);
		} else {
			timeSet.add(times["1"].dateTime);
			searchTime = getTime(times["1"].dateTime);
		}

		timeSet.forEach((time) => {
			startTimeDropdown.appendChild(createDropdownItem(time, time));
		});

		updateRooms();
	});
};

const updateRooms = () => {
	roomDropdown.textContent = "";
	roomSet.clear();

	getRooms().then((rooms) => {
		if (Array.isArray(rooms)) {
			rooms.forEach((room) => {
				roomSet.add(room.roomName);
			});
			searchRoom = rooms[0].roomName;
		} else {
			roomSet.add(rooms["1"].roomName);
			searchRoom = rooms["1"].roomName;
		}

		roomSet.forEach((room) => {
			roomDropdown.appendChild(createDropdownItem(room, room));
		});

		resRoom.innerHTML = searchRoom;
		updateTech();
	});
};

const updateTech = () => {
	techDropdown.textContent = "";
	techSet.clear();

	getTech().then(async (techs) => {
		if (Array.isArray(techs)) {
			techs.forEach((tech) => {
				techSet.add(tech.idTech);
			});
			let fullTech = await getTechnology(techs[0].idTech);
			searchTech = fullTech.idTech;
		} else {
			techSet.add(techs["1"].idTech);
			searchTech = fullTech.idTech;
		}

		techSet.forEach(async (idTech) => {
			let tech = await getTechnology(idTech);
			techDropdown.appendChild(createDropdownItem(idTech, tech.name));
		});

		searchPro = techs[0].idPro;
		updateProjection();
	});
};

const updateProjection = () => {
	getSpecProjection().then((data) => {
		let projection = data[0];

		rowsEl.value = projection.numberOfRows;
		colsEl.value = projection.seatsInRow;
		priceEl.value = projection.price;

		renderSeats();
		fillReserved(projection.idPro);
		attachSeatListeners();
	});
};

const triggerModal = () => {
	updateProjections();
	document.getElementById("sellTicketModalWrapper").classList.add("showModal");
};

const getRowNum = (rowNumber) => {
	return rowNumber.charCodeAt(0) - 65 + 1;
};

const getSeatNum = (seatNumber) => {
	return Number(seatNumber);
};

const prepareSeats = () => {
	let seats = "";

	let cnt = 0;
	selectedSeats.forEach((seat, indx, set) => {
		seats += `${getRowNum(seat.charAt(0))}:${getSeatNum(seat.substring(1))}`;
		cnt++;
		if (cnt < set.size) seats += " ";
	});

	return seats;
};

const confirmWorkerReservation = async (idPro) => {
	let preparedSeats = prepareSeats();

	let postData = {
		idPro: idPro,
		seats: preparedSeats
	};

	let fd = new FormData();
	for (let i in postData) {
		fd.append(i, postData[i]);
	}

	let response = await fetch(`http://` + window.location.host + `/worker/confirm`, {
		method: "POST",
		body: fd,
		mode: "cors"
	});

	let data = await response.json();
	return data;
};

const initiateWorkerConfirm = () => {
	confirmButton.classList.add("reservationButtonDisabled");

	confirmWorkerReservation(searchPro).then((data) => {
		if (data == "OK") {
			confirmSuccess.classList.remove("hideModal");
			setTimeout(() => {
				confirmSuccess.classList.add("hideModal");
			}, 5000);
		} else {
			confirmError.classList.remove("hideModal");
			setTimeout(() => {
				confirmError.classList.add("hideModal");
			}, 5000);
		}

		selectedSeats.clear();
		updateSeatsText();
		updateSelected();
		resetPrice();
		updateProjection();

		confirmButton.classList.remove("reservationButtonDisabled");
	});
};
