document.addEventListener("DOMContentLoaded", function() {
    // removing alert notification after 5s
    let alert = document.getElementsByClassName('alert')[0];
    if (typeof alert !== 'undefined') {
        setTimeout(function () {
            alert.remove()
        }, 5000)
    }
});