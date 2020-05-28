/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/

let addAdminWrapper = document.getElementById('addAdminWrapper');
let addAdminModal = document.getElementById('addAdminModal');
let deleteModalWrapper = document.getElementById('deleteModalWrapper');
let deleteModal = document.getElementById('deleteModal');
let confirmModalWrapper = document.getElementById('confirmModalWrapper');
let confirmModal = document.getElementById('confirmModal');

if (addAdminWrapper != null)
    addAdminWrapper.addEventListener(('click'), (e) => {
        hideModal('addAdminWrapper');
        return false;
    });

if (addAdminModal != null)
    addAdminModal.addEventListener(('click'), (e) => {
        e.stopPropagation();
        return false;
    });

if (deleteModalWrapper != null)
    deleteModalWrapper.addEventListener(('click'), (e) => {
        hideModal('deleteModalWrapper');
        return false;
    });

if (deleteModal != null)
    deleteModal.addEventListener(('click'), (e) => {
        e.stopPropagation();
        return false;
    });

if (confirmModalWrapper != null)
    confirmModalWrapper.addEventListener(('click'), (e) => {
        hideModal('confirmModalWrapper');
        return false;
    });

if (confirmModal != null)
    confirmModal.addEventListener(('click'), (e) => {
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
