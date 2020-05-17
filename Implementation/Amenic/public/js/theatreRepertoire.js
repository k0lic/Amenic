/*
    Author: Andrija Kolić
    Github: k0lic
*/

// VARIABLES //

const repertoireTable = document.getElementById("showingTable");
const repertoireShowing = document.getElementById("repertoireShowing");
const repertoireSoon = document.getElementById("repertoireSoon");
const repertoireHeader = document.getElementById("repertoireHeader");
const leftArrow = document.getElementById("leftArrow");
const rightArrow = document.getElementById("rightArrow");
const firstPage = document.getElementById("firstPage");
const firstDots = document.getElementById("firstDots");
const previousPage = document.getElementById("previousPage");
const currentPage = document.getElementById("currentPage");
const nextPage = document.getElementById("nextPage");
const lastDots = document.getElementById("lastDots");
const lastPage = document.getElementById("lastPage");

const uiPageSize = 5;

var thisCinema = null;
var selectedDate = null;
var collection = [];
var pageNum = 1;
var totalItems = 0;
var mode = 0;

// EVENT LISTENERS //

function setupOnLoad(email, date) {
    console.log("seting up for: " + email);
    thisCinema = email;
    dateChanged(date);
}

function selectDate(str) {
    calendarClick(str);
    dateChanged(str);
}

function changeMode(nextMode) {
    if (mode != nextMode) {
        modeChanged(nextMode);
    }
}

function pageBack() {
    console.log("Trying to change the page!");
    if (pageNum > 1) {
        pageChanged(pageNum - 1);
    }
}

function pageForward() {
    console.log("Trying to change the page!");
    if (pageNum < maxPageNumber()) {
        pageChanged(pageNum + 1);
    }
}

function pageFirst() {
    console.log("Trying to change the page!");
    if (pageNum > 1) {
        pageChanged(1);
    }
}

function pageLast() {
    console.log("Trying to change the page!");
    if (pageNum < maxPageNumber()) {
        pageChanged(maxPageNumber());
    }
}

// STATE UPDATE FUNCTIONS //

function dateChanged(newDate) {
    let tmp = getDate(newDate);
    //selectedDate = new Date(Number(tmp.year), Number(tmp.month) - 1, Number(tmp.day));
    selectedDate = tmp.year + "-" + tmp.month + "-" + tmp.day;
    if (mode == 0) {
        collection = [];
        pageNum = 1;
        totalItems = 0;
        firstMovieRun(thisCinema, selectedDate, pageNum);
    }
}

function modeChanged(newMode) {
    mode = newMode;
    collection = [];
    pageNum = 1;
    totalItems = 0;
    if (mode == 0) {
        firstMovieRun(thisCinema, selectedDate, pageNum);
    } else {
        firstComingSoonRun(thisCinema, pageNum);
    }
    updateTableTitles();
    updateRepertoireHeaders();
}

function pageChanged(newPage) {
    pageNum = newPage;
    console.log("pageNum= "+pageNum+"; totalItems= "+totalItems+"; maxPageNumber= "+maxPageNumber());
    let firstIndex = (pageNum - 1) * uiPageSize;
    if (firstIndex >= collection.length || collection[firstIndex] == null) {
        if (mode == 0) {
            movieRun(thisCinema, selectedDate, pageNum);
        } else {
            comingSoonRun(thisCinema, pageNum);
        }
    } else {
        populateRepertoire();
        updatePageNumberControls();
    }
}

function movieRun(email, date, page) {
    getMovieRepertoire(email, date, page).then((data) => { saveFetchedItems(data); populateRepertoire(); });
    updatePageNumberControls();
}

function comingSoonRun(email, page) {
    getComingSoonRepertoire(email, page).then((data) => { saveFetchedItems(data); populateRepertoire(); });
    updatePageNumberControls();
}

function firstMovieRun(email, date, page) {
    countMovieRepertoire(email, date).then((data) => {
        totalItems = data;
        console.log("totalItems= "+totalItems);
        movieRun(email, date, page);
    });
}

function firstComingSoonRun(email, page) {
    countComingSoonRepertoire(email).then((data) => {
        totalItems = data;
        console.log("totalItems= "+totalItems);
        comingSoonRun(email, page);
    });
}

function saveFetchedItems(items) {
    let i = (pageNum - 1) * uiPageSize;
    items.forEach(item => {
        collection[i++] = item;
    });
}

// CALCULATORS //

function getDate(str) {
    let date = str + "";
    let year = date.slice(0, 4);
    let month = date.slice(4, 6);
    let day = date.slice(6);
    return {
        year: year,
        month: month,
        day: day
    }
}

function maxPageNumber() {
    return Math.floor((totalItems + uiPageSize - 1) / uiPageSize);
}

// DOM MODIFIERS //

function calendarClick(clicked) {
    // update the buttons
    let btns = document.getElementsByClassName("todayButton");
    if (btns.length > 0) {
        btns[0].classList.remove("selectedDay");
    }

    btns = document.getElementsByClassName("dayButton");
    for (let i = 0; i < btns.length; i++) {
        btns[i].classList.remove("selectedDay");
    }

    let chosenOne = document.getElementById("buttonDate_" + clicked);
    chosenOne.classList.add("selectedDay");

    // parse the date
    let date = getDate(clicked);

    // update the 'selected' field
    let visualUpdate = document.getElementById("selectedDate");
    visualUpdate.textContent = date.day + "/" + date.month + "/" + date.year;
}

function updateTableTitles() {
    if (mode == 0) {
        repertoireShowing.classList.add("repertoireTableTitleSelected");
        repertoireSoon.classList.remove("repertoireTableTitleSelected");
    } else {
        repertoireSoon.classList.add("repertoireTableTitleSelected");
        repertoireShowing.classList.remove("repertoireTableTitleSelected");
    }
}

function updateRepertoireHeaders() {
    let content = "";
    if (mode == 0) {
        content = "" +
            "<div class=\"w30 textCenter\">Name</div>" +
            "<div class=\"w15 textCenter\">Time</div>" +
            "<div class=\"w20 textCenter\">Room</div>" +
            "<div class=\"w15 textCenter\">Type</div>" +
            "<div class=\"w20 textCenter\">Free seats</div>";
    } else {
        content = "<div class=\"w100 textCenter\">Name</div>";
    }
    repertoireHeader.innerHTML = content;
}

function updatePageNumberControls() {
    if (pageNum == 1) {
        leftArrow.classList.remove("movieSearchActiveControl");
    } else {
        leftArrow.classList.add("movieSearchActiveControl");
    }

    if (pageNum > 2) {
        firstPage.classList.remove("hidden");
        if (pageNum > 3) {
            firstDots.classList.remove("hidden");
        } else {
            firstDots.classList.add("hidden");
        }
    } else {
        firstPage.classList.add("hidden");
        firstDots.classList.add("hidden");
    }

    if (pageNum > 1) {
        previousPage.classList.remove("hidden");
        previousPage.innerText = pageNum - 1;
    } else {
        previousPage.classList.add("hidden");
    }

    currentPage.innerText = pageNum;

    if (pageNum < maxPageNumber()) {
        nextPage.classList.remove("hidden");
        nextPage.innerText = pageNum + 1;
    } else {
        nextPage.classList.add("hidden");
    }

    if (pageNum < maxPageNumber() - 1) {
        if (pageNum < maxPageNumber() - 2) {
            lastDots.classList.remove("hidden");
        } else {
            lastDots.classList.add("hidden");
        }
        lastPage.classList.remove("hidden");
        lastPage.innerText = maxPageNumber();
    } else {
        lastDots.classList.add("hidden");
        lastPage.classList.add("hidden");
    }

    if (pageNum >= maxPageNumber()) {
        rightArrow.classList.remove("movieSearchActiveControl");
    } else {
        rightArrow.classList.add("movieSearchActiveControl");
    }
}

function populateRepertoire() {
    let content = "";
    let i = (pageNum - 1) * uiPageSize;
    let cnt = 0;
    while (i < collection.length && cnt < uiPageSize) {
        if (mode == 0) {
            content += movieTemplate(collection[i]);
        } else {
            content += soonTemplate(collection[i]);
        }
        i++;
        cnt++;
    }
    repertoireTable.innerHTML = content;
}

// DOM TEMPLATES //

function movieTemplate(projection) {
    return "" +
        "<a class=\"anchorWrapper\" href=\"/reservation/" + projection.idPro + "\">" +
        "<div class=\"showingTableRow row centerY mb-1\">" +
        "<div class=\"w30 column centerRow\">" + projection.movieName + "</div>" +
        "<div class=\"w15 textCenter\">" + projection.startTime + "</div>" +
        "<div class=\"w20 textCenter\">" + projection.roomName + "</div>" +
        "<div class=\"w15 textCenter\">" + projection.type + "</div>" +
        "<div class=\"w20 textCenter\">" + projection.freeSeats + "</div>" +
        "</div>" +
        "</a>";
}

function soonTemplate(soon) {
    return "" +
        "<a class=\"anchorWrapper\" href=\"#\">" +
        "<div class=\"showingTableRow row centerY mb-1\">" +
        "<div class=\"w100 column centerRow\">" + soon.movieName + "</div>" +
        "</div>" +
        "</a>";
}

// FETCH METHODS //

async function getMovieRepertoire(email, day, page) {
    //let urlEncodeEmail = email.replace(/@/g, "%40");
    //urlEncodeEmail = urlEncodeEmail.replace(/\./g, "%2E");
    //console.log(urlEncodeEmail);
    let response = await fetch(
        "http://localhost:8080/Theatre/getMyRepertoire?cinemaEmail=" + email + "&day=" + day + "&page=" + page,
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};

async function getComingSoonRepertoire(email, page) {
    let response = await fetch(
        "http://localhost:8080/Theatre/getMyComingSoons?cinemaEmail=" + email + "&page=" + page,
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};

async function countMovieRepertoire(email, day) {
    let response = await fetch(
        "http://localhost:8080/Theatre/countMyRepertoire?cinemaEmail=" + email + "&day=" + day,
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};

async function countComingSoonRepertoire(email) {
    let response = await fetch(
        "http://localhost:8080/Theatre/countMyComingSoons?cinemaEmail=" + email,
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};