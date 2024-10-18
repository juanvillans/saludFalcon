export default function clickOutside(element, callbackFunction) {
    function onClick(event) {
        console.log(element, event.target)
        if (!element.contains(event.target)) {
            callbackFunction();
        }
    }
    
    document.addEventListener('click', onClick);
    
    return {
        update(newCallbackFunction) {
            callbackFunction = newCallbackFunction;
        },
        destroy() {
            document.body.removeEventListener('click', onClick);
        }
    }
}