/*
    Author: Andrija Kolić
    Github: k0lic
*/

// VARIABLES //

const movieNameInput = document.getElementById("movieNameInput");
const tmdbIDInput = document.getElementById("tmdbIDInput");
const movieSearchResultsContainer = document.getElementById("movieSearchResultsContainer");
const movieSearchResults = document.getElementById("movieSearchResults");
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
const dbPageSize = 20;
var isHidden = true;
var newTermGracePeriod = null;

var searchTerm = null;
var totalResultsInHouse = null;
var totalResultsTMDB = null;
var movies = [];
var pageNum = null;
var exploredTheEnd = false;
var duplicates = 0;
var tmdbIDset = new Set();

// COMPUTED //

function maxPageNumber() {
    if (totalResultsInHouse == null) {
        return 1;
    } else if (totalResultsTMDB == null) {
        return 1 + Math.floor(totalResultsInHouse / uiPageSize);
    } else {
        return 1 + Math.floor((totalResultsInHouse + totalResultsTMDB - duplicates - 1) / uiPageSize);
    }
};

function movieAfterLast() {
    if (totalResultsInHouse == null) {
        return 0;
    } else if (totalResultsTMDB == null) {
        return totalResultsInHouse;
    } else {
        return totalResultsInHouse + totalResultsTMDB - duplicates;
    }
}

function nextTMDBpage() {
    if (totalResultsTMDB == null) {
        return 1;
    } else {
        return 1 + (movies.length - totalResultsInHouse + duplicates) / dbPageSize;
    }
}

// EVENT LISTENERS //

function setupListeners() {
    document.body.addEventListener("click", hideSearchResultsContainer);
    movieNameInput.addEventListener("input", newSearchTerm);
    movieNameInput.addEventListener("click", (e) => { e.stopPropagation(); });
    movieSearchResultsContainer.addEventListener("click", (e) => { e.stopPropagation(); });
    leftArrow.addEventListener("click", pageBack);
    rightArrow.addEventListener("click", pageForward);
    firstPage.addEventListener("click", goToFirstPage);
    previousPage.addEventListener("click", pageBack);
    nextPage.addEventListener("click", pageForward);
    lastPage.addEventListener("click", goToLastPage);
}

function hideSearchResultsContainer() {
    if (!isHidden) {
        movieSearchResultsContainer.classList.remove("movieDropdownVisible");
        isHidden = true;
    }
}

function newSearchTerm() {
    if (isHidden) {
        movieSearchResultsContainer.classList.add("movieDropdownVisible");
        isHidden = false;
    }

    let newTerm = movieNameInput.value;
    if (newTerm != searchTerm) {
        clearTimeout(newTermGracePeriod);
        newTermGracePeriod = setTimeout(function () {
            updateSearchTerm(newTerm);
        }, 300);
        generateDummyContent();
    }
}

function pageBack() {
    if (pageNum > 1) {
        pageNum--;
        pageChanged();
    }
}

function pageForward() {
    if (pageNum < maxPageNumber()) {
        pageNum++;
        pageChanged();
    }
}

function goToFirstPage() {
    if (pageNum > 1) {
        pageNum = 1;
        pageChanged();
    }
}

function goToLastPage() {
    if (pageNum < maxPageNumber()) {
        pageNum = maxPageNumber();
        pageChanged();
    }
}

function chooseMovie(num) {
    movieNameInput.value = movies[num].title;
    tmdbIDInput.value = movies[num].tmdbID;
    addMovieIfNotExisting(movies[num].tmdbID);
    if (!isHidden) {
        movieSearchResultsContainer.classList.remove("movieDropdownVisible");
        isHidden = true;
    }
}

function showMoreMovies() {
    if (totalResultsTMDB == null) {
        getTmdbMoviesThenUpdateVarThenUpdateDom();
    }
}

// STATE UPDATE FUNCTIONS //

function updateSearchTerm(newTerm) {
    searchTerm = newTerm;
    countHowManyMoviesLike(searchTerm).then((data) => { totalResultsInHouse = data; });
    totalResultsTMDB = null;
    movies = [];
    pageNum = 1;
    exploredTheEnd = false;
    duplicates = 0;
    tmdbIDset.clear();
    getMoviesThenUpdateVarThenUpdateDom();
}

function pageChanged() {
    let firstMovieToShow = (pageNum - 1) * uiPageSize;
    if (firstMovieToShow < movieAfterLast() && (movies.length <= firstMovieToShow || movies[firstMovieToShow] == null)) {
        getMoviesThenUpdateVarThenUpdateDom();
    } else {
        if (totalResultsTMDB != null && pageNum == maxPageNumber()) {
            exploredTheEnd = true;
        }
        generateContent();
        updatePageNumberControls();
    }
}

function getMoviesThenUpdateVarThenUpdateDom() {
    if (totalResultsTMDB == null) {
        getMoviesLike(searchTerm, pageNum).then((data) => { updateMovies(data); generateContent(); updatePageNumberControls(); });
    } else {
        getTmdbMoviesThenUpdateVarThenUpdateDom();
    }
}

function getTmdbMoviesThenUpdateVarThenUpdateDom() {
    let batchDuplicates = 20;
    getMoviesLikeInTMDB(searchTerm, nextTMDBpage()).then((data) => {
        if (totalResultsTMDB == null) {
            totalResultsTMDB = data.total_results;
        }
        batchDuplicates = updateMoviesFromTmdbObject(data.results);
        duplicates += batchDuplicates;
        if (batchDuplicates <= 15 || (nextTMDBpage() - 1) * dbPageSize >= totalResultsTMDB) {
            if (pageNum == maxPageNumber()) {
                exploredTheEnd = true;
            }
            generateContent();
            updatePageNumberControls();
        } else {
            getTmdbMoviesThenUpdateVarThenUpdateDom();
        }
    });
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
        tmdbIDset.add(Number(movie.tmdbID));
    });
}

function updateMoviesFromTmdbObject(moreMovies) {
    let batchDuplicates = 0;
    moreMovies.forEach(movie => {
        if (tmdbIDset.has(Number(movie.id))) {
            batchDuplicates++;
        } else {
            movies.push({
                title: movie.title,
                released: movie.release_date,
                poster: movie.poster_path == null ? null : "https://image.tmdb.org/t/p/original/" + movie.poster_path,
                tmdbID: movie.id
            });
            tmdbIDset.add(Number(movie.id));
        }
    });
    return batchDuplicates;
}

// DOM MODIFIERS //

function generateContent() {
    let i = (pageNum - 1) * uiPageSize;
    let content = "";

    for (let j = 0; j < uiPageSize; j++) {
        if (i >= movies.length) {
            if (totalResultsTMDB == null) {
                content += refreshDbItem();
            }
            break;
        } else {
            content += searchItemTemplate(i);
            i++;
        }
    }

    movieSearchResults.innerHTML = content;
}

function searchItemTemplate(index) {
    return "<li class=\"movieSearchResultItem\" onClick=\"chooseMovie(" + index + ")\">"
        + "<div class=\"row centerY spaceBetween movieSearchResultItemInner\">"
        + "<img src=\"" + (movies[index].poster == null ? "https://via.placeholder.com/95x140" : movies[index].poster) + "\" class=\"movieSearchImg\" />"
        + "<div class=\"column ml-3\">"
        + "<div class=\"movieSearchItemHeader mb-1\">Title:</div>"
        + "<div class=\"movieSearchItemText\">" + movies[index].title + "</div>"
        + "</div>"
        + "<div class=\"column ml-3\">"
        + "<div class=\"movieSearchItemHeader mb-1\">Release date:</div>"
        + "<div class=\"movieSearchItemText\">" + movies[index].released + "</div>"
        + "</div>"
        + "</div>"
        + "</li>";
}

function refreshDbItem() {
    return "<li class=\"movieSearchResultItem movieSearchItemEmpty\" onClick=\"showMoreMovies()\">"
        + "<div class=\"row centerRow movieSearchResultItemInner\">"
        + "<div class=\"column\">"
        + "<div class=\"movieSearchItemText mb-1\">Haven't found what you were looking for?</div>"
        + "<div>Click here to refresh our database.</div>"
        + "</div>"
        + "</div>"
        + "</li>";
}

function generateDummyContent() {
    movieSearchResults.innerHTML = "<li class=\"movieSearchResultItem movieSearchItemEmpty movieSearchItemText row centerRow\"><div>...</div></li>"
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

    if (exploredTheEnd && pageNum < maxPageNumber() - 1) {
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

    if (pageNum == maxPageNumber()) {
        rightArrow.classList.remove("movieSearchActiveControl");
    } else {
        rightArrow.classList.add("movieSearchActiveControl");
    }
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

const getMoviesLikeInTMDB = async function (match, page) {
    let response = await fetch(
        "http://localhost:8080/Cinema/getMoviesLikeInTMDB?match=" + match + "&page=" + page,
        {
            method: "GET",
            mode: "cors"
        }
    );

    let data = await response.json();
    return data;
};

const addMovieIfNotExisting = async function (tmdbID) {
    fetch(
        "http://localhost:8080/Cinema/addMovieIfNotExisting?tmdbID=" + tmdbID,
        {
            method: "GET",
            mode: "cors"
        }
    );
}