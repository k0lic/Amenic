/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

function createListElement(data, actMenu) {
	let form = document.createElement("form");
	form.action = "javascript:void(0);";
	form.method = "POST";
	form.className = "rowWrapper";

	let userPicture = document.createElement("div");
	userPicture.className = "userPicture";

	let img = document.createElement("img");
	img.src =
		data.image == null
			? "/assets/profPic.png"
			: "data:image/jpg;base64, " + data.image;

	userPicture.appendChild(img);
	form.appendChild(userPicture);

	let description = document.createElement("div");
	description.className = "description";

	let descriptionH1 = document.createElement("div");
	let name = document.createElement("h1");
	name.innerHTML = !data.hasOwnProperty("name")
		? data.firstName + " " + data.lastName
		: data.name;
	descriptionH1.appendChild(name);
	description.appendChild(descriptionH1);

	let descriptionSpan = document.createElement("div");
	let span = document.createElement("span");
	span.innerHTML =
		data.email +
		(!data.hasOwnProperty("address") || data.address == null
			? ""
			: " • " + data.address) +
		(!data.hasOwnProperty("cityName") || data.cityName == null
			? ""
			: " • " + data.cityName) +
		(!data.hasOwnProperty("countryName") || data.countryName == null
			? ""
			: " • " + data.countryName) +
		(!data.hasOwnProperty("phoneNumber") || data.phoneNumber == null
			? ""
			: " • " + data.phoneNumber);

	descriptionSpan.appendChild(span);
	description.appendChild(descriptionSpan);
	form.appendChild(description);

	let editWrapper = document.createElement("div");
	editWrapper.className = "editWrapper";

	let binWrapper = document.createElement("div");
	binWrapper.className = "binWrapper";

	let pencilIcon = document.createElement("img");
	pencilIcon.src = "/assets/Admins/pencil.svg";
	pencilIcon.alt = "Img error!";

	let binIcon = document.createElement("img");
	binIcon.src = "/assets/Admins/bin.svg";
	binIcon.alt = "Img error!";

	let actMenuHidden = document.createElement("input");
	actMenuHidden.type = "hidden";
	actMenuHidden.value = actMenu;
	let keyHidden = document.createElement("input");
	keyHidden.type = "hidden";
	keyHidden.value = data.email;

	if (actMenu == 1) {
		let button = document.createElement("button");
		button.formAction = "/AdminController/editRequest";

		button.appendChild(pencilIcon);
		editWrapper.appendChild(button);
		editWrapper.appendChild(actMenuHidden);
		editWrapper.appendChild(keyHidden);
		form.appendChild(editWrapper);
	}
	if (actMenu == 0 || actMenu == 1) {
		let button = document.createElement("button");
		button.onclick = "showModal(" + data.email + "," + actMenu + ") ";

		button.appendChild(binIcon);
		binWrapper.appendChild(button);
		form.appendChild(binWrapper);
	}
	if (actMenu == 2) {
		let button = document.createElement("button");
		button.formAction = "/AdminController/editRequest";

		button.appendChild(pencilIcon);
		binWrapper.appendChild(button);
		binWrapper.appendChild(actMenuHidden);
		binWrapper.appendChild(keyHidden);
		form.appendChild(binWrapper);
	}

	return form;
}

const searchBar = document.getElementById("searchBar");
const actMenu = document.getElementById("actMenu");
const list = document.getElementById("list");

const getUsers = async () => {
	let postData = {
		actMenu: actMenu.value,
		phrase: searchBar.value
	};
	var fd = new FormData();
	for (var i in postData) {
		fd.append(i, postData[i]);
	}

	let response = await fetch(`http://localhost:8080/AdminController/search`, {
		method: "POST",
		body: fd,
		mode: "cors"
	});

	let data = await response.json();

	while (list.firstChild) {
		list.removeChild(list.lastChild);
	}

	for (let i = 0; i < data.length; i++) {
		let element = createListElement(data[i], actMenu.value);
		list.appendChild(element);
	}

	return;
};

searchBar.addEventListener("input", getUsers);
