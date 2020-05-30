/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

let selectedSeats = new Set();
let total = 0;

const reservedSeats = document.getElementById("reservedSeats");
const totalPrice = document.getElementById("totalPrice");
const numSeats = document.getElementById("numSeats");
const idPro = document.getElementById("idPro").value;
const modalSeats = document.getElementById("reservationModalSeats");
const modalTotal = document.getElementById("reservationModalTotal");
const resConfirm = document.getElementById("resConfirm");
const resSuccess = document.getElementById("resSuccess");
const resError = document.getElementById("resError");
const resTaken = document.getElementById("resTaken");
const confirmButton = document.getElementById("confirmButton");
const ticketPrice = parseFloat(document.getElementById("ticketPrice").value);

document.getElementById("resConfirm").addEventListener("click", (e) => {
	e.stopPropagation();
	return false;
});

document.getElementById("resSuccess").addEventListener("click", (e) => {
	e.stopPropagation();
	return false;
});

document.getElementById("resError").addEventListener("click", (e) => {
	e.stopPropagation();
	return false;
});

// Based on seatingPreview.js
const renderSeats = () => {
	let rows = document.getElementById("projectionRows").value;
	let columns = document.getElementById("projectionColumns").value;
	let seats = rows * columns;

	let target = document.getElementById("projectionSeating");

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

renderSeats();

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

attachSeatListeners();

const addSelected = (seat) => {
	if (!selectedSeats.has(seat)) {
		document.getElementById(seat).classList.add("selectedProjectionSeat");
		selectedSeats.add(seat);
		updateSelected();

		if (selectedSeats.size > 6) {
			showSeatError();
		}
		updateSeatsText();
		updatePrice(ticketPrice);
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
		updatePrice(-ticketPrice);
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
		.getElementById("reservationButton")
		.classList.remove("reservationButtonDisabled");
};

const disableReservationButton = () => {
	document
		.getElementById("reservationButton")
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
			.getElementById("reservationButton")
			.classList.remove("reservationButtonDisabled");
	}
};

disableReservationButton();
updateSeatsText();

const updatePrice = (offset) => {
	total += offset;
	totalPrice.innerHTML = `€${total.toFixed(2)}`;
};

updatePrice(0);

const getReservations = async () => {
	let response = await fetch(
		`http://localhost:8080/reservation/getReservations?idPro=${idPro}`,
		{
			method: "GET",
			mode: "cors"
		}
	);

	let data = await response.json();
	return data;
};

let reservations = [];

const fillReserved = () => {
	reservations.splice(0, reservations);
	getReservations().then((data) => {
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

const markUnavailable = (seat) => {
	document.getElementById(seat).classList.add("seatTaken");
};

fillReserved();

const updateReservationModal = () => {
	// Clear the seats
	modalSeats.textContent = "";

	let cnt = 0;
	selectedSeats.forEach((seat, indx, set) => {
		modalSeats.innerHTML += seat;
		cnt++;
		if (cnt < set.size) modalSeats.innerHTML += ", ";
	});

	modalTotal.innerHTML = `€${total.toFixed(2)}`;
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

const confirmReservation = async () => {
	let preparedSeats = prepareSeats();

	let postData = {
		idPro: idPro,
		seats: preparedSeats
	};

	let fd = new FormData();
	for (let i in postData) {
		fd.append(i, postData[i]);
	}

	let response = await fetch(`http://localhost:8080/reservation/confirm`, {
		method: "POST",
		body: fd,
		mode: "cors"
	});

	let data = await response.json();
	return data;
};

const initiateConfirm = () => {
	confirmButton.classList.add("reservationButtonDisabled");

	confirmReservation().then((data) => {
		if (data == "OK") {
			resConfirm.classList.add("hideModal");
			resSuccess.classList.remove("hideModal");
		} else if (data == "TAKEN") {
			resConfirm.classList.add("hideModal");
			resTaken.classList.remove("hideModal");
		} else {
			resConfirm.classList.add("hideModal");
			resError.classList.remove("hideModal");
		}

		confirmButton.classList.remove("reservationButtonDisabled");
	});
};

const closeResModal = () => {
	document.getElementById("reservationModal").classList.remove("showModal");
	resSuccess.classList.add("hideModal");
	resError.classList.add("hideModal");
	resConfirm.classList.remove("hideModal");
};

const msgCloseModal = () => {
	closeResModal();
	location.reload();
};
