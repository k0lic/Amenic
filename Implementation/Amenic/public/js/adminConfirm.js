function showModal(mail, actMenu) {

    let element = document.getElementById('loginModal');
    element.style.display = "block";

    console.log(mail);
    console.log(actMenu);

    let el = document.getElementById("key");
    if (el == null) {
        let inputKey = document.createElement("input");
        let inputMenu = document.createElement("input");
        inputKey.setAttribute('type', 'hidden');
        inputKey.setAttribute('name', 'key');
        inputKey.setAttribute('value', mail);
        inputKey.setAttribute('id', "key");
        inputMenu.setAttribute('type', 'hidden');
        inputMenu.setAttribute('name', 'actMenu');
        inputMenu.setAttribute('value', actMenu);
        inputMenu.setAttribute('id', "actMenu");
        document.getElementById('loginModal').appendChild(inputKey);
        document.getElementById('loginModal').appendChild(inputMenu);
    }
    else {
        console.log(el);
        el.setAttribute('value', mail);
        el = document.getElementById("actMenu");
        console.log(el);
        el.setAttribute('value', actMenu);
    }
}

function hideModal(el) {
    let element = document.getElementById(el);
    element.style.display = "none";
}

function showAddAdmin() {

    let element = document.getElementById('addAdminModal');
    element.style.display = "block";
}
