/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

let el = document.getElementsByClassName("cityDropdownItem");

for (let element of el) {
	element.addEventListener("click", (e) => {
		e.preventDefault();
		console.log(e.target.innerHTML);
		document.getElementById("citySelect").innerHTML = e.target.innerHTML;
	});
}
