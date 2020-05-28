/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

let adminWrapper = document.getElementById('addAdminWrapper');
let adminModal = document.getElementById('addAdminModal');

adminWrapper.addEventListener(('click'), (e) => {
    hideModal('addAdminWrapper');
    return false;
});

adminModal.addEventListener(('click'), (e) => {
    e.stopPropagation();
    return false;
});

function showModal(mail, actMenu) {

    console.log(mail);
    console.log(actMenu);

    let element = document.getElementById('deleteModalWrapper');
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
        document.getElementById('deleteModal').appendChild(inputKey);
        document.getElementById('deleteModal').appendChild(inputMenu);
    }
    else {
        el.setAttribute('value', mail);
        el = document.getElementById("actMenu");
        el.setAttribute('value', actMenu);
    }
}

function hideModal(el) {
    let element = document.getElementById(el);
    element.style.display = "none";
}

function showSpecModal(el) {
    let element = document.getElementById(el);
    element.style.display = "block";
}
