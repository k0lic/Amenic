/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

function createListElement(data, subMenu) {
    let a = document.createElement('a');
    a.className = "coolLink";
    let img = document.createElement("img");
    img.className = "movieImg";

    if (subMenu === "") {
        a.href = "/Theatre/Repertoire/" + data.email;
        let div = document.createElement('div');
        div.className = 'guestCardExtended';
        img.src = data.banner == null ? "/assets/Cinema/cinema.jpg" : "data:image/jpeg;base64, " + data.banner;
        let textDiv = document.createElement('div');
        textDiv.className = "text-block";
        let paragraph = document.createElement('p');
        paragraph.innerHTML = data.name;
        textDiv.appendChild(paragraph);
        div.appendChild(img);
        div.appendChild(textDiv);
        a.appendChild(div);
        return a;
    }
    else {
        let div = document.createElement('div');
        div.className = 'guestCardExtended';
        img.src = data.poster;
        a.href = "/movie/" + data.tmdbID;
        div.appendChild(img);
        a.appendChild(div);
        return a;
    }
}

const searchBar = document.getElementById("searchBar");
const list = document.getElementById("list");
const countryListSearch = document.getElementById("countryList");
const cityListSearch = document.getElementById('cityList');
let activeMenus = document.getElementsByClassName("activeMenu");

const getMoviesAndCinemas = async () => {
    let path = "";
    let menu = "";
    let aM = 0;


    for (let i = 0; i < activeMenus.length; i++) {
        if (activeMenus[i].innerHTML === "Movies") {
            aM = 0;
            continue;
        }
        if (activeMenus[i].innerHTML === "Cinemas") {
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

    if (aM) {
        path = `http://localhost:8080/HomeController/cinemasSearch/${countryListSearch.selectedIndex}/${cityListSearch[cityListSearch.selectedIndex].value}/${searchBar.value}`;
    }
    else {
        path = `http://localhost:8080/HomeController/titleSearch?actMenu=${menu}&title=${searchBar.value}`;
    }


    let response = await fetch(
        path,
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
if (countryListSearch != null) {
    countryListSearch.addEventListener('change', function () {
        //reset city index after changing country
        cityList.selectedIndex = 0;
        getMoviesAndCinemas();
    });
    cityListSearch.addEventListener('change', getMoviesAndCinemas);
}