/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

function createListElement(data, subMenu) {
    let a = document.createElement('a');
    a.className = "coolLink";
    let img = document.createElement("img");
    a.appendChild(img);
    img.className = "movieImg";

    if (subMenu === "") {
        let div = document.createElement('div');
        div.className = 'cinemaContainer';
        a.href = "/Cinema/cinemaPage"; // + data.email;
        img.src = data.banner == null ? "/assets/Cinema/cinema.jpg" : "data:image/jpeg;base64, " + data.banner;
        let textDiv = document.createElement('div');
        textDiv.className = "text-block";
        let paragraph = document.createElement('p');
        paragraph.innerHTML = data.name;
        textDiv.appendChild(paragraph);
        a.appendChild(textDiv);
        div.appendChild(a);
        return div;
    }
    else {
        img.src = data.poster;
        a.href = "/movie/" + data.tmdbID;
        return a;
    }
}

const searchBar = document.getElementById("searchBar");
const list = document.getElementById("list");
let activeMenus = document.getElementsByClassName("activeMenu");

const getMoviesAndCinemas = async () => {
    let path = "";
    let menu = "";
    let aM = 0;


    for (let i = 0; i < activeMenus.length; i++) {
        if (activeMenus[i].innerHTML === "Movies") {
            path = "/HomeController/titleSearch";
            aM = 0;
            continue;
        }
        if (activeMenus[i].innerHTML === "Cinemas") {
            path = "/HomeController/nameSearch";
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

    while (list.firstChild) {
        list.removeChild(list.lastChild);
    };

    for (let i = 0; i < data.length; i++) {
        let element = createListElement(data[i], menu);
        list.appendChild(element);
    }

    return;
};


searchBar.addEventListener('input', getMoviesAndCinemas);

