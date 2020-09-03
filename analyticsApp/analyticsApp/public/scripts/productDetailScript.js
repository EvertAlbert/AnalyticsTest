let initProductDetailScript = () => {
    startTimer();
};

let time = 0;
let interVal;
let path = window.location.pathname;
let productId = path.split("/").pop();

let timerHandler = () => {
    time++
};

let startTimer = () => {
    interVal = window.setInterval(timerHandler,1000);
};

let stopTimer = () => {
    window.clearInterval(interVal);
};

document.addEventListener('DOMContentLoaded', initProductDetailScript);
window.addEventListener('focus', startTimer);
window.addEventListener('blur', stopTimer);
window.addEventListener('beforeunload', (e)=>{
    e.preventDefault();
    logAction(visitorId, `viewTime`, `${productId}_${time}`);
});