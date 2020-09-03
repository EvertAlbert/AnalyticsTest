let visitorId = 0;

const initAnalytics = (e) => {
    if (sessionStorage.getItem(`uniqueId`) === null) {
        visitorId = uuidv4();
        sessionStorage.setItem(`uniqueId`,`${visitorId}`);
    } else {
        visitorId = sessionStorage.getItem(`uniqueId`);
    } //generate id when new user connects
};

let logAction = (visitorId, userEvent, customMessage) => {
    let cmd = {
        visitorId : visitorId,
        action : userEvent,
        message : customMessage,
        page: window.location.pathname
    };
    let cmdAsString = JSON.stringify(cmd);

    ws.send(cmdAsString);
};

window.addEventListener("beforeunload", (e) => {
    e.preventDefault();
    logAction(visitorId, `disconnect`, window.location.href);
}); //actions that happen when user disconnects


/**** WEBSOCKET INTERACTION ****/

let ws = new WebSocket(`ws://homestead.test:1234`);
ws.addEventListener(`open`, e => {
    console.log(`connection opened`);
    logAction(visitorId, `connect`, window.location.href);

}); //actions that happen when connection with socket is made

ws.addEventListener(`close`, e => {
    console.log(`connection closed`);
}); //other actions that happen when connection with socket is closed

ws.addEventListener(`error`, e => {
    console.error(`error: ${e.data}`)
}); //actions that happen when an error occurs

ws.addEventListener(`message`, e => {
    console.log(`message received: ${e.data}`);
}); //actions that happen when page gets message

document.addEventListener(`DOMContentLoaded`, initAnalytics());


function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
} //TODO find better way to generate guid to replace this solution from stackOverflow: https://stackoverflow.com/questions/105034/create-guid-uuid-in-javascript
