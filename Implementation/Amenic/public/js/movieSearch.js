/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

function createListElement(data, actMenu) {
    let a = document.createElement('a');
    a.className = "coolLink";
    let img = document.createElement("img");
    img.src = actMenu === "0" ? "data:image/jpeg;base64, " + data.banner : data.poster;
    img.className = "movieImg";
    a.appendChild(img);
    a.href = "/movie/" + data.tmdbID;

    return a;
}

const searchBar = document.getElementById("searchBar");
const list = document.getElementById("list");


const getMovies = async () => {
    let path = "";
    let menu = "";
    let aM = 0;

    let activeMenus = document.getElementsByClassName("activeMenu");
    for (let i = 0; i < activeMenus.length; i++) {
        if (activeMenus[i].innerHTML === "Movies") {
            path = "/HomeController/titleSearch";
            aM = 0;
            continue;
        }
        if (activeMenus[i].innerHTML === "Cinemas") {
            path = "";
            aM = 1;
            continue;
        }
        if (activeMenus[i].innerHTML === "Now playing") {
            menu = "1";
            continue;
        }
        if (activeMenus[i].innerHTML === "Coming soon") {
            menu = "2";
            continue;
        }
    }

    let response = await fetch(
        `http://localhost:8080${path}?actMenu=${menu}&title=${searchBar.value}`,
        {
            method: 'GET',
            mode: "cors"
        }
    );

    let data = await response.json();
    console.log(data);

    while (list.firstChild) {
        list.removeChild(list.lastChild);
    };

    for (let i = 0; i < data.length; i++) {
        let element = createListElement(data[i], aM);
        list.appendChild(element);
        console.log(element);
    }

    return;
};

searchBar.addEventListener('input', getMovies);

