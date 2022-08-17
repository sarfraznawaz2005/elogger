window.notyf = new Notyf({duration: 5000, position: {x: 'right', y: 'bottom'}, ripple: false, dismissible: true});

window.paceOptions = {ajax: false, elements: false, restartOnPushState: false, restartOnRequestAfter: false}

function getMinutesBetweenDates(date, startTime, endTime) {
    let startDate = new Date(date.value + ' ' + startTime.value);
    let endDate = new Date(date.value + ' ' + endTime.value);

    const diff = endDate.getTime() - startDate.getTime();

    return diff ? ((diff / 60000) / 60).toFixed(2) : '0.00';
}

function sendBrowserEvent(eventName, triggerEventName, value, title) {
    document.dispatchEvent(new CustomEvent(eventName, {
        detail: {
            event: triggerEventName,
            value: value,
            title: title ? title : 'Are you sure you want to delete ?'
        }
    }));
}
