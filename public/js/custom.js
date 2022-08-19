window.notyf = new Notyf({duration: 5000, position: {x: 'right', y: 'bottom'}, ripple: false, dismissible: true});

window.paceOptions = {ajax: false, elements: false, restartOnPushState: false, restartOnRequestAfter: false}

function sendBrowserEvent(eventName, triggerEventName, value, title) {
    document.dispatchEvent(new CustomEvent(eventName, {
        detail: {
            event: triggerEventName,
            value: value,
            title: title ? title : 'Are you sure you want to delete ?'
        }
    }));
}
