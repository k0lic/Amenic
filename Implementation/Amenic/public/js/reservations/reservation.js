/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

function updateSeatingPreview() {
	let rowsInput = document.getElementById("seatingRows");
	let columnsInput = document.getElementById("seatingColumns");

	let rows = rowsInput.value;
	let columns = columnsInput.value;
	let seats = rows * columns;

	let target = document.getElementById("seatingPreview");

	if (seats == 0 || rows > 26 || columns > 26) {
		target.style.width = "80%";
		target.style.height = "60px";
		target.style.gridTemplateColumns = "1fr";
		target.style.gridTemplateRows = "1fr";

		if (seats == 0) {
			target.innerHTML =
				'<div class="centerItalic">There are no seats in this room yet!</div>';
		} else {
			target.innerHTML =
				'<div class="centerItalic">There are too many seats in this room!</div>';
		}
	} else {
		target.style.gridTemplateColumns =
			"repeat(" + (Number(columns) + 1) + ", 1fr)";
		target.style.gridTemplateRows = "repeat(" + (Number(rows) + 1) + ", 1fr)";

		let cellSize = 30;
		let width = columns * cellSize;
		let height = rows * cellSize;

		target.style.width = width + "px";
		target.style.height = height + "px";

		let content = "";

		for (let i = 0; i < rows; i++) {
			let rowLabel = String.fromCharCode(65 + i);
			content += '<div class="centerSeatLabels">' + rowLabel + "</div>";
			for (let j = 0; j < columns; j++) {
				let columnLabel = j + 1;
				content += '<div class="seat"></div>';
			}
		}

		content += "<div></div>";
		for (let j = 0; j < columns; j++) {
			let columnLabel = j + 1;
			content += '<div class="centerSeatLabels">' + columnLabel + "</div>";
		}

		target.innerHTML = content;
	}
}
