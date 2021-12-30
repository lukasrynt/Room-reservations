class Dialog
{
    constructor(dialog) {
        this.dialog = dialog;
    }

    show() {
        this.hidden = false;
        this.dialog.style = 'display: block;';
    }

    hide() {
        this.hidden = true;
        this.dialog.style = 'display: none;';
    }
}

function removeUser(element) {
    let reservationId = parseInt(element.getAttribute('data-reservation-id'));
    let userId = parseInt(element.getAttribute('data-user-id'));
    fetch('/api/reservations/' + reservationId + '/attendees/' + userId, { method: 'DELETE' })
        .then((response) => {
            if (response.ok) {
                element.parentElement.remove();
                document.getElementById('add-reservation-user').classList.remove('hidden');
            }
        })
}

function createUserElement(user, reservationId) {
    let template = document.getElementById('ajax-template');
    let userElement = template.cloneNode(true);
    userElement.removeAttribute('id');
    let link = userElement.getElementsByTagName('a')[0];
    link.addEventListener('click', () => removeUser(link))
    link.setAttribute('data-reservation-id', reservationId);
    link.setAttribute('data-user-id', user.id);
    userElement.append(user['username']);
    return userElement;
}

function addUser(element, user) {
    let reservationId = parseInt(element.getAttribute('data-reservation-id'));
    fetch('/api/reservations/' + reservationId + '/attendees/' + user['id'], { method: 'PUT' })
        .then((response) => {
            if (response.ok) {
                let userEl = createUserElement(user, reservationId);
                let listItem = element.parentElement;
                listItem.parentElement.insertBefore(userEl, listItem);
            }
        })
}

async function fetchTakenIds(reservationId) {
    let response = await fetch('/api/reservations/' + reservationId);
    if (!response.ok) {
        return [];
    }
    const json = await response.json();
    return json['attendees'].map((user) => user['id'])
}

async function fetchAllUsers() {
    let response = await fetch('/api/users/');
    if (!response.ok) {
        return [];
    }
    return await response.json();
}

async function fetchAvailableAttendees(reservationId) {
    let takenIds = await fetchTakenIds(reservationId);
    let allUsers = await fetchAllUsers();
    return allUsers.filter((user) => !takenIds.includes(user.id))
}

function createUsersDialog() {
    let dialog = document.getElementById('modal-template');
    return new Dialog(dialog);
}

document.addEventListener("DOMContentLoaded", function() {
    Array.prototype.forEach.call(document.getElementsByClassName('remove-reservation-user'), (el) => {
        el.addEventListener('click', () => removeUser(el))
    })
    let addBtn = document.getElementById('add-reservation-user');
    let reservationId = addBtn.getAttribute('data-reservation-id')
    let dialog = createUsersDialog();
    addBtn.parentElement.append(dialog.dialog);
    dialog.hide();
    addBtn.addEventListener('click', () => {
        fetchAvailableAttendees(reservationId).then((users) => {
            if (!users.length) {
                addBtn.classList.add('hidden');
                return;
            }
            let usersList = document.getElementById('users-list')
            usersList.innerHTML = "";
            users.forEach((user) => {
                let userLink = document.createElement('a');
                userLink.innerText = user['username'];
                userLink.addEventListener('click', () => {
                    addUser(addBtn, user);
                    dialog.hide();
                });
                usersList.append(userLink);
            })
            dialog.show();
        });
    });
})