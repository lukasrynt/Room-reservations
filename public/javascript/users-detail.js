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
       el.addEventListener('mouseenter', () => {
           let template = document.getElementById('detail-modal-template');
           let userDetail = template.cloneNode(true);
           userDetail.removeAttribute('id');
           getUser(el).then((user) => {
               userDetail.getElementsByClassName('user-name')[0].innerText = user['first_name'] + " " + user["last_name"];
               userDetail.getElementsByClassName('user-first-name')[0].innerText = user['first_name'];
               userDetail.getElementsByClassName('user-last-name')[0].innerText = user['last_name'];
               userDetail.getElementsByClassName('user-username')[0].innerText = user['username'];
               el.append(userDetail);
           })
       });
       el.addEventListener('mouseleave', () => {
           el.getElementsByClassName('detail-modal')[0].remove();
       });
    });
});