function areYouSure(msg, action) {

    let modalWrapper = document.getElementById('areYouSureWrapper');
    modalWrapper.classList.add("showModal");

    let modalMessage = document.getElementById('areYouSureMessage');
    modalMessage.innerText = msg;

    let modalAction = document.getElementById('areYouSureAction');
    modalAction.formAction = action;

}

function iAmNotSure() {

    let modalWrapper = document.getElementById('areYouSureWrapper');
    modalWrapper.classList.remove("showModal");

}

function stopModalPropagation() {

    document.getElementById('areYouSureModal').addEventListener(('click'), (e) => {
        e.stopPropagation();
        return false;
    });

}