async function getUser(element) {
    let userId = parseInt(element.getAttribute('data-user-id'));
    const response = await fetch('/api/users/' + userId);
    if (response.ok) {
        return await response.json();
    } else {
        return null;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    Array.prototype.forEach.call(document.getElementsByClassName('user-row'), (el) => {
       el.addEventListener('click', () => {
           let template = document.getElementById('detail-modal-template');

           getUser(el).then((user) => {
               template.getElementsByClassName('user-name')[0].innerText = user['first_name'] + " " + user["last_name"];
               template.getElementsByClassName('user-first-name')[0].innerText = user['first_name'];
               template.getElementsByClassName('user-last-name')[0].innerText = user['last_name'];
               template.getElementsByClassName('user-username')[0].innerText = user['username'];
               template.getElementsByClassName('user-note')[0].innerText = user['note'];
               template.style.display = 'block';

               let closeButton = template.querySelector('.close-button');
               closeButton.addEventListener('click', () => {
                   console.log('close');
                   template.style.display = 'none';
               });
           })
       });
    });
});