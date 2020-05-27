/*
    Author: Andrija Kolić
    Github: k0lic
*/

const carouselModal = document.getElementById("carouselModal");
const leftCarouselArrow = document.getElementById("leftCarouselArrow");
const rightCarouselArrow = document.getElementById("rightCarouselArrow");
const carouselImages = document.getElementById("carouselImages");
const imageNameForDelete = document.getElementById("imageNameForDelete");

var currentSlide = 0;

function openCarousel(startWith) {

    let modalWrapper = document.getElementById('carouselWrapper');
    modalWrapper.classList.add("showModal");

    goToCarouselImage(startWith);

}

function carouselForward() {
    let nextSlide = currentSlide + 1;
    goToCarouselImage(nextSlide);
}

function carouselBack() {
    let nextSlide = currentSlide - 1;
    goToCarouselImage(nextSlide);
}

function closeCarousel() {

    let modalWrapper = document.getElementById('carouselWrapper');
    modalWrapper.classList.remove("showModal");

}

function stopCarouselPropagation() {

    document.getElementById('carouselModal').addEventListener(('click'), (e) => {
        e.stopPropagation();
        return false;
    });

}

function goToCarouselImage(nextSlide) {
    let children = carouselImages.children;

    if (nextSlide < 0 || nextSlide >= children.length) {
        return;
    }

    children[currentSlide].classList.add("galleryHidden");

    currentSlide = nextSlide;

    children[currentSlide].classList.remove("galleryHidden");
    imageNameForDelete.value = children[currentSlide].id;

    if (currentSlide == 0) {
        leftCarouselArrow.classList.remove("movieSearchActiveControl");
    } else {
        leftCarouselArrow.classList.add("movieSearchActiveControl");
    }

    if (currentSlide == children.length - 1) {
        rightCarouselArrow.classList.remove("movieSearchActiveControl");
    } else {
        rightCarouselArrow.classList.add("movieSearchActiveControl");
    }

}