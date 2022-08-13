window.notyf = new Notyf({duration: 5000, position: {x: 'right', y: 'bottom'}, ripple: false, dismissible: true});

window.paceOptions = {ajax: false, elements: false, restartOnPushState: false, restartOnRequestAfter: false}

function getMinutesBetweenDates(startDate, endDate) {
    const diff = endDate.getTime() - startDate.getTime();

    return ((diff / 60000) / 60).toFixed(2);
}
