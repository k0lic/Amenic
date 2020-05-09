function selectDate(caller) {

    let btns = document.getElementsByClassName("todayButton");
    if (btns.length > 0) {
        btns[0].classList.remove("selectedDay");
    }

    btns = document.getElementsByClassName("dayButton");
    for (let i=0;i<btns.length;i++) {
        btns[i].classList.remove("selectedDay");
    }

    let chosenOne = document.getElementById("buttonDate_"+caller);
    chosenOne.classList.add("selectedDay");

    let date = caller+"";
    let year = date.slice(0,4);
    let month = date.slice(4,6);
    let day = date.slice(6);

    let visualUpdate = document.getElementById("selectedDate");
    visualUpdate.textContent = day + "/" + month + "/" + year;
    let inputUpdate = document.getElementById("selectedDateHidden");
    inputUpdate.value = day + "/" + month + "/" + year;
}



/*
function showPicture() {

    let file = document.getElementById("profilePicture");
    console.log(file['files'][0]);

    if (file && file['files'] && file['files'][0]) {
        let img = document.getElementById('adminPic');
        img.src = URL.createObjectURL(file.files[0]);
    }

}*/