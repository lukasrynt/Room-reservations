function removeUser(element) {
    let reservationId = parseInt(element.getAttribute('data-reservation-id'));
    let userId = parseInt(element.getAttribute('data-user-id'));
    fetch('/api/reservations/' + reservationId + '/attendees/' + userId, { method: 'DELETE' })
        .then((response) => {
            if (response.ok) {
                element.parentElement.remove();
            }
        })
}

document.addEventListener("DOMContentLoaded", function() {
    Array.prototype.forEach.call(document.getElementsByClassName('remove-reservation-user'), (el) => {
        el.addEventListener('click', () => removeUser(el))
    })
})