/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

const pencilSvg = " <svg viewBox=\"-264 116.7 469.3 469.3\"><path class=\"st0\" d=\"M192.8,192.8l-64-64.1c-16.1-16.1-44.2-16.2-60.4,0l-286.7,289.5c-1.3,1.3-2.2,2.9-2.7,4.6l-42.7,149.5 c-1.1,3.7,0,7.7,2.7,10.5c2,2,4.8,3.1,7.5,3.1c1,0,2-0.1,2.9-0.4l149.3-42.7c1.7-0.5,3.3-1.4,4.6-2.7l289.3-287 c8.1-8.1,12.5-18.8,12.5-30.2S200.9,200.9,192.8,192.8z M22,206.4l39.3,39.3l-205,205l-14.7-29.4c-1.8-3.6-5.5-5.9-9.5-5.9h-17.1 L22,206.4z M-237.8,559.8l13.9-48.6l34.7,34.7L-237.8,559.8z M-114.7,524.6l-51,14.6l-51.5-51.5l14.6-51h28l18.4,36.8 c1,2.1,2.7,3.7,4.8,4.8l36.8,18.4L-114.7,524.6L-114.7,524.6z M-93.3,507.1V490c0-4-2.3-7.7-5.9-9.5l-29.4-14.7l205-205l39.3,39.3	L-93.3,507.1z M177.8,238.4l-47,46.6L37,191.3l46.6-47c8.1-8.1,22.1-8.1,30.2,0l64,64c4,4,6.3,9.4,6.3,15.1	S181.8,234.4,177.8,238.4z\"/></svg>";

const binSvg = "<svg viewBox=\"-286 137 346.8 427\"><path d=\"M-53.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189C-43.6,296.2-48.1,291.7-53.6,291.7 z\"></path><path d=\"M-171.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-161.6,296.2-166.1,291.7-171.6,291.7z\"></path><path d=\"M-257.6,264.1v246.4c0,14.6,5.3,28.2,14.7,38.1c9.3,9.8,22.2,15.4,35.7,15.4H-18c13.5,0,26.4-5.6,35.7-15.4 c9.3-9.8,14.7-23.5,14.7-38.1V264.1c18.5-4.9,30.6-22.8,28.1-41.9C58,203.2,41.8,189,22.6,189h-51.2v-12.5 c0.1-10.5-4.1-20.6-11.5-28c-7.4-7.4-17.6-11.6-28.1-11.5H-157c-10.5-0.1-20.6,4-28.1,11.5c-7.4,7.4-11.6,17.5-11.5,28V189h-51.2 c-19.2,0-35.4,14.2-37.9,33.3C-288.2,241.3-276.1,259.2-257.6,264.1z M-18,544h-189.2c-17.1,0-30.4-14.7-30.4-33.5V265h250v245.5 C12.4,529.3-0.9,544-18,544z M-176.6,176.5c-0.1-5.2,2-10.2,5.7-13.9c3.7-3.7,8.7-5.7,13.9-5.6h88.8c5.2-0.1,10.2,1.9,13.9,5.6 c3.7,3.7,5.7,8.7,5.7,13.9V189h-128V176.5z M-247.8,209H22.6c9.9,0,18,8.1,18,18s-8.1,18-18,18h-270.4c-9.9,0-18-8.1-18-18 S-257.7,209-247.8,209z\"></path><path d=\"M-112.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-102.6,296.2-107.1,291.7-112.6,291.7z\"></path></svg>";

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

	let actMenuHidden = document.createElement("input");
	actMenuHidden.type = "hidden";
	actMenuHidden.name = "actMenu";
	actMenuHidden.value = actMenu;
	let keyHidden = document.createElement("input");
	keyHidden.type = "hidden";
	keyHidden.name = "key";
	keyHidden.value = data.email;

	if (actMenu == 1) {
		let button = document.createElement("button");
		button.classList.add("highlightSvgOnHover");
		button.formAction = "/AdminController/editRequest";

		button.innerHTML = pencilSvg;
		editWrapper.appendChild(button);
		editWrapper.appendChild(actMenuHidden);
		editWrapper.appendChild(keyHidden);
		form.appendChild(editWrapper);
	}
	if (actMenu == 0 || actMenu == 1) {
		let button = document.createElement("button");
		button.classList.add("highlightSvgOnHover");
		button.addEventListener('click', function () {
			showModal(data.email, actMenu);
			document.getElementById('binWrapper').addEventListener(('click'), (e) => {
				e.stopPropagation();
				return false;
			});
			return false;
		});

		button.innerHTML = binSvg;
		binWrapper.appendChild(button);
		form.appendChild(binWrapper);
	}
	if (actMenu == 2) {
		let button = document.createElement("button");
		button.classList.add("highlightSvgOnHover");
		button.setAttribute('formAction', "/AdminController/editRequest");

		button.innerHTML = pencilSvg;
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

	let response = await fetch(`http://` + window.location.host + `/AdminController/search`, {
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

function stopModalPropagation(el) {

	document.getElementById('el').addEventListener(('click'), (e) => {
		e.stopPropagation();
		return false;
	});

}