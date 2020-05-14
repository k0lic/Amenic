
// VARIABLES //

const movieNameInput = document.getElementById("movieNameInput");
const movieSearchResultsContainer = document.getElementById("movieSearchResultsContainer");
const movieSearchResults = document.getElementById("movieSearchResults");
const leftArrow = document.getElementById("leftArrow");
const rightArrow = document.getElementById("rightArrow");
var myTimeout = null;
var isHidden = true;

var searchTerm = null;
var totalResultsInHouse = null;
var totalResultsTMDB = null;
var movies = [];
var pageNum = null;
const uiPageSize = 5;

// EVENT LISTENERS //

function setupListeners() {
    movieNameInput.addEventListener("focusin", showSearchResultsContainer);
    movieNameInput.addEventListener("focusout", hideSearchResultsContainer);
    movieNameInput.addEventListener("input", newSearchTerm);
    movieSearchResultsContainer.addEventListener("click", (e) => { doNotLoseInputFocus(e); });
    leftArrow.addEventListener("click", pageBack);
    rightArrow.addEventListener("click", pageForward);
    console.log("I did my best");
}

function showSearchResultsContainer() {
    if (isHidden) {
        movieSearchResultsContainer.classList.add("movieDropdownVisible");
        isHidden = false;
    }
}

function hideSearchResultsContainer() {
    myTimeout = setTimeout(function () {
        if (!isHidden) {
            movieSearchResultsContainer.classList.remove("movieDropdownVisible");
            isHidden = true;
        }
    }, 100);
}

function doNotLoseInputFocus(e) {
    clearTimeout(myTimeout);
    let vertScrollPosition = document.scrollingElement.scrollTop;
    movieNameInput.focus();
    document.scrollingElement.scrollTop = vertScrollPosition;
}

function newSearchTerm() {
    let newTerm = movieNameInput.value;
    console.log("Searching for: " + newTerm);
    if (newTerm != searchTerm) {
        updateSearchTerm(newTerm);
    }
}

function pageBack() {
    if (pageNum > 1) {
        pageNum--;
        pageChanged();
    }
}

function pageForward() {
    if (totalResultsInHouse != null && pageNum < (totalResultsInHouse+1) / uiPageSize) {
        pageNum++;
        pageChanged();
    }
}

// STATE UPDATE FUNCTIONS //

function updateSearchTerm(newTerm) {
    searchTerm = newTerm;
    countHowManyMoviesLike(searchTerm).then((data) => { totalResultsInHouse = data; });
    totalResultsTMDB = null;
    movies = [];
    pageNum = 1;
    getMoviesLike(searchTerm, pageNum).then((data) => {
        console.log("Here's what we got: "+data);
        updateMovies(data);
        generateContent();
    });
}

function pageChanged() {
    console.log("Page number is now: " + pageNum);
    console.log("Total movie number is: " + totalResultsInHouse);
    let firstMovieToShow = (pageNum - 1) * uiPageSize;
    if (movies.length <= firstMovieToShow || movies[firstMovieToShow] == null)
        getMoviesThenUdateVarThenUpdateDom();
    else
        generateContent();
}

function getMoviesThenUdateVarThenUpdateDom() {
    getMoviesLike(searchTerm, pageNum).then((data) => { updateMovies(data); generateContent(); });
}

function updateMovies(moreMovies) {
    let i = (pageNum - 1) * uiPageSize;
    moreMovies.forEach(movie => {
        movies[i++] = {
            title: movie.title,
            released: movie.released,
            poster: movie.poster,
            tmdbID: movie.tmdbID
        };
    });
    console.log(moreMovies);
}

// DOM MODIFIERS //

function generateContent() {
    let i = (pageNum - 1) * uiPageSize;
    let content = "";

    for (let j = 0; j < uiPageSize; j++) {
        if (i >= movies.length) {
            content += refreshDbItem();
            break;
        } else {
            content += searchItemTemplate(movies[i]);
            i++;
        }
    }

    movieSearchResults.innerHTML = content;
}

function searchItemTemplate(item) {
    return "<li class=\"movieSearchResultItem\">"
        + "<div class=\"row centerY spaceBetween movieSearchResultItemInner\">"
        + "<img src=\"" + item.poster + "\" class=\"movieSearchImg\" />"
        + "<div class=\"column ml-3\">"
        + "<div class=\"movieSearchItemHeader mb-1\">Title:</div>"
        + "<div class=\"movieSearchItemText\">" + item.title + "</div>"
        + "</div>"
        + "<div class=\"column ml-3\">"
        + "<div class=\"movieSearchItemHeader mb-1\">Release date:</div>"
        + "<div class=\"movieSearchItemText\">" + item.released + "</div>"
        + "</div>"
        + "</div>"
        + "</li>";
}

function refreshDbItem() {
    return "<li class=\"movieSearchResultItem movieSearchItemEmpty\">"
        + "<div class=\"row centerRow movieSearchResultItemInner\">"
        + "<div class=\"column\">"
        + "<div class=\"movieSearchItemText mb-1\">Haven't found what you were looking for?</div>"
        + "<div>Click here to refresh our database.</div>"
        + "</div>"
        + "</div>"
        + "</li>";
}

// FETCH METHODS //

const getMovies = async () => {
    let response = await fetch(
        "http://localhost:8080/Cinema/getSomeMovies?",
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};

const countHowManyMoviesLike = async function (match) {
    let response = await fetch(
        "http://localhost:8080/Cinema/countHowManyMoviesLike?match=" + match,
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};

const getMoviesLike = async function (match, page) {
    let response = await fetch(
        "http://localhost:8080/Cinema/getMoviesLike?match=" + match + "&page=" + page,
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};