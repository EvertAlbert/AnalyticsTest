const rateGoodButton = document.querySelector(`#rateGood`);
const rateMehButton = document.querySelector(`#rateMeh`);
const rateBadButton = document.querySelector(`#rateBad`);

const thanksField = document.querySelector(`#thanksMessage`);

let initRating = () => {
    rateGoodButton.addEventListener('click', () => {rate(2)});
    rateMehButton.addEventListener('click', () => {rate(1)});
    rateBadButton.addEventListener('click', () => {rate(0)});
};

let rate = (rating) => {
    logAction(visitorId, `rate`, rating);
    thanksField.innerText = `Thank you for your feedback`
};

document.addEventListener("DOMContentLoaded", initRating);
