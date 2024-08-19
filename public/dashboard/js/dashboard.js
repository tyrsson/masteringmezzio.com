// todo: move this to a notification.js file to load on both front and backend
const systemMessage = function (level, msg) {
    const template = document.querySelector('#systemMessageTemplate');
    const clone    = template.content.firstElementChild.cloneNode(true);
    //clone.classList.add(`bg-${level ?? 'info'}`, "fw-bold");
    clone.querySelector('.toast-header').classList.add(`bg-${level ?? 'info'}`);
    clone.querySelector('.toast-body').innerHTML = msg;
    const container = document.querySelector('#systemMessage');
    const messenger = new bootstrap.Toast(clone, {autohide: true, delay: 2000});
    container.appendChild(clone);
    messenger.show();
};
// handle the server triggered systemMessage event
htmx.on("systemMessage", evt => systemMessage(evt.detail.level, evt.detail.message));

// Request Error handling
htmx.on("htmx:responseError", evt => systemMessage('danger', evt.detail.xhr.responseText));

