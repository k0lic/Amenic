/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

const countryList = document.getElementById("countryList");
const cityList = document.getElementById('cityList');

function generateCity(data) {
    var x = document.createElement("OPTION");
    x.value = data.idCity;
    x.innerHTML = data.name;
    return x;
}

const getCities = async () => {
    //console.log(countryList[countryList.selectedIndex].innerHTML);

    let response = await fetch(
        `http://localhost:8080/HomeController/getCities/${countryList.selectedIndex}`,
        {
            method: 'GET',
            mode: "cors"
        }
    );

    let data = await response.json();

    while (cityList.firstChild) {
        cityList.removeChild(cityList.lastChild);
    };

    let zeroOption = generateCity({ idCity: 0, name: "" });
    cityList.appendChild(zeroOption);
    for (let i = 0; i < data.length; i++) {
        let element = generateCity(data[i]);
        cityList.appendChild(element);
    }
    cityList.selectedIndex = 0;

    return;
};


countryList.addEventListener('change', getCities);

