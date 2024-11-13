const dismissMessage = function() {
    const modal = document.body.querySelector('#systemMessage');
    if (modal) {
        const closeButton = document.body.querySelector('#dismissSystemMessage');
        closeButton.addEventListener('click', evt => modal.close());
    }
};
htmx.on("htmx:afterSettle", evt => dismissMessage());