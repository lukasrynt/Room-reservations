function hide(element) {
    element.classList.add('hidden');
}

function show(element) {
    element.classList.remove('hidden');
}

function removeUser(element) {
    let groupId = parseInt(element.getAttribute('data-group-id'));
    let userId = parseInt(element.getAttribute('data-user-id'));
    fetch('/api/groups/' + groupId + '/users/' + userId, { method: 'DELETE' })
        .then((response) => {
            if (response.ok) {
                element.parentElement.remove();
                show(document.getElementById('add-group-user'));
            }
        })
}

function createUserElement(user, groupId) {
    let template = document.getElementById('ajax-template-user');
    let userElement = template.cloneNode(true);
    userElement.removeAttribute('id');
    let link = userElement.getElementsByTagName('a')[0];
    link.addEventListener('click', () => removeUser(link))
    link.setAttribute('data-group-id', groupId);
    link.setAttribute('data-user-id', user.id);
    userElement.append(user['username']);
    return userElement;
}

function addUser(element, user) {
    let groupId = parseInt(element.getAttribute('data-group-id'));
    fetch('/api/groups/' + groupId + '/users/' + user['id'], { method: 'PUT' })
        .then((response) => {
            if (response.ok) {
                let userEl = createUserElement(user, groupId);
                let listItem = element.parentElement;
                listItem.parentElement.insertBefore(userEl, listItem);
            }
        })
}

async function fetchTakenUserIds(groupId) {
    let response = await fetch('/api/groups/' + groupId);
    if (!response.ok) {
        return [];
    }
    const json = await response.json();
    return json['members'].map((user) => user['id'])
}

async function fetchAllUsers() {
    let response = await fetch('/api/users/');
    if (!response.ok) {
        return [];
    }
    return await response.json();
}

async function fetchAvailableMembers(groupId) {
    let takenIds = await fetchTakenUserIds(groupId);
    let allUsers = await fetchAllUsers();
    return allUsers.filter((user) => !takenIds.includes(user.id))
}

function createUsersDialog() {
    let template = document.getElementById('modal-template-members');
    let dialog = template.cloneNode(true);
    dialog.getElementsByTagName('button')[0].addEventListener('click', (e) => dialog.close())
    dialog.removeAttribute('id');
    return dialog;
}

document.addEventListener("DOMContentLoaded", function() {
    Array.prototype.forEach.call(document.getElementsByClassName('remove-group-user'), (el) => {
        el.addEventListener('click', () => removeUser(el))
    })
    let addBtn = document.getElementById('add-group-user');
    if (!addBtn)
        return;
    let groupId = addBtn.getAttribute('data-group-id')
    let dialog = createUsersDialog();
    addBtn.parentElement.append(dialog);
    dialog.close();
    hide(addBtn);
    fetchAvailableMembers(groupId).then((users) => {
        if (users.length)
            show(addBtn);
    });
    addBtn.addEventListener('click', () => {
        fetchAvailableMembers(groupId).then((users) => {
            if (!users.length) {
                hide(addBtn)
                return;
            }
            let usersList = dialog.getElementsByTagName('ul')[0];
            usersList.innerHTML = "";
            users.forEach((user) => {
                let userLink = document.createElement('a');
                userLink.innerText = user['username'];
                userLink.addEventListener('click', () => {
                    addUser(addBtn, user);
                    if (users.length === 1)
                        hide(addBtn);
                    dialog.close();
                });
                usersList.append(userLink);
            })
            dialog.showModal();
        });
    });
})