// htmx.onLoad((e) => {
//     const isOpenClass       = "modal-is-open";
//     const openingClass      = "modal-is-opening";
//     const closingClass      = "modal-is-closing";
//     const scrollbarWidthVar = "--pico-scrollbar-width";
//     const animationDuration = 400;
//     let visibleModal = null;

//     let closeButton = htmx.find(e, '#system-message-close');
//     if (closeButton !== null) {
//         const { documentElement: html } = document;
//         //html.classList.add(isOpenClass, openingClass);
//         let closeListener = htmx.on(
//             '#system-message-close',
//             'click',
//             (evt) => {
//                 const modal = htmx.closest(closeButton, 'dialog');
//                 console.log('close clicked', evt);
//                 closeSystemMessage(modal);
//             }
//         );

//         const closeSystemMessage = (modal) => {
//             visibleModal = null;
//             const { documentElement: html } = document;
//             html.classList.add(closingClass);
//             setTimeout(() => {
//               html.classList.remove(closingClass, isOpenClass);
//               //html.style.removeProperty(scrollbarWidthCssVar);
//               modal.close();
//             }, animationDuration);
//         };
//     }
// });