const langSelect = document.querySelector(`.langSelect`);
const ageSelect = document.querySelector(`.ageSelect`);
const dutchButton = document.querySelector(`#dutchButton`);
const englishButton = document.querySelector(`#englishButton`);
const frenchButton = document.querySelector(`#frenchButton`);
const ageButton = document.querySelector(`#ageSubmit`);
const ageInput = document.querySelector(`#ageInput`);

const init = (e) => {
    dutchButton.addEventListener('click', (e) => {
        e.preventDefault();
        logAction(visitorId, `language`, `NL`);
        hideSection(langSelect);
        showSection(ageSelect);
    });
    englishButton.addEventListener('click', (e) => {
        e.preventDefault();
        logAction(visitorId, `language`, `ENG`);
        hideSection(langSelect);
        showSection(ageSelect);
    });
    frenchButton.addEventListener('click', (e) => {
        e.preventDefault();
        logAction(visitorId, `language`, `FRA`);
        hideSection(langSelect);
        showSection(ageSelect);
    });
    ageButton.addEventListener(`click`, (e) => {
        e.preventDefault();
        let age = ageInput.value;
        logAction(visitorId, `age`, `${age}`);
        location.href = '/products';

    })
};

let hideSection = (element) => {
    element.classList.remove(`visible`);
    element.classList.add(`hidden`);
};

let showSection = (element) => {
    element.classList.remove(`hidden`);
    element.classList.add(`visible`);
};

document.addEventListener(`DOMContentLoaded`, init());
