function hide(element) {
    element.classList.add('hidden');
}

function show(element) {
    element.classList.remove('hidden');
}

function removeRoom(element) {
    let groupId = parseInt(element.getAttribute('data-group-id'));
    let roomId = parseInt(element.getAttribute('data-room-id'));
    fetch('/api/groups/' + groupId + '/rooms/' + roomId, { method: 'DELETE' })
        .then((response) => {
            if (response.ok) {
                element.parentElement.remove();
                show(document.getElementById('add-group-room'));
            }
        })
}

function createRoomElement(room, groupId) {
    let template = document.getElementById('ajax-template-room');
    let roomElement = template.cloneNode(true);
    roomElement.removeAttribute('id');
    let link = roomElement.getElementsByTagName('a')[0];
    link.addEventListener('click', () => removeRoom(link))
    link.setAttribute('data-group-id', groupId);
    link.setAttribute('data-room-id', room.id);
    let roomNameLink = roomElement.getElementsByTagName('a')[1];
    roomNameLink.setAttribute('href', '/rooms/' + room['id']);
    roomNameLink.innerText = room['name'];
    return roomElement;
}

function addGroup(element, room) {
    let groupId = parseInt(element.getAttribute('data-group-id'));
    fetch('/api/groups/' + groupId + '/rooms/' + room['id'], { method: 'PUT' })
        .then((response) => {
            if (response.ok) {
                let userEl = createRoomElement(room, groupId);
                let listItem = element.parentElement;
                listItem.parentElement.insertBefore(userEl, listItem);
            }
        })
}

async function fetchRoomTakenIds(groupId) {
    let response = await fetch('/api/groups/' + groupId);
    if (!response.ok) {
        return [];
    }
    const json = await response.json();
    return json['rooms'].map((room) => room['id'])
}

async function fetchAllRooms() {
    let response = await fetch('/api/rooms/');
    if (!response.ok) {
        return [];
    }
    return await response.json();
}

async function fetchAvailableRooms(groupId) {
    let takenIds = await fetchRoomTakenIds(groupId);
    let allRooms = await fetchAllRooms();
    return allRooms.filter((room) => !takenIds.includes(room.id))
}

function createRoomsDialog() {
    let template = document.getElementById('modal-template-rooms');
    let dialog = template.cloneNode(true);
    dialog.getElementsByTagName('button')[0].addEventListener('click', (e) => dialog.close())
    dialog.removeAttribute('id');
    return dialog;
}

document.addEventListener("DOMContentLoaded", function() {
    Array.prototype.forEach.call(document.getElementsByClassName('remove-group-room'), (el) => {
        el.addEventListener('click', () => removeRoom(el))
    })
    let addBtn = document.getElementById('add-group-room');
    if (!addBtn)
        return;
    let groupId = addBtn.getAttribute('data-group-id')
    let dialog = createRoomsDialog();
    addBtn.parentElement.append(dialog);
    dialog.close();
    hide(addBtn);
    fetchAvailableRooms(groupId).then((rooms) => {
        if (rooms.length)
            show(addBtn);
    });
    addBtn.addEventListener('click', () => {
        fetchAvailableRooms(groupId).then((rooms) => {
            if (!rooms.length) {
                hide(addBtn)
                return;
            }
            let roomsList = dialog.getElementsByTagName('ul')[0];
            roomsList.innerHTML = "";
            rooms.forEach((room) => {
                let roomLink = document.createElement('a');
                roomLink.innerText = room['name'];
                roomLink.addEventListener('click', () => {
                    addGroup(addBtn, room);
                    if (rooms.length === 1)
                        hide(addBtn);
                    dialog.close();
                });
                roomsList.append(roomLink);
            })
            dialog.showModal();
        });
    });
})