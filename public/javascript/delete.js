function buildMessage(element) {
    let action = element.getAttribute('data-confirm');
    let name = element.getAttribute('data-name');
    return 'Are you sure you want to ' + action + ' ' + name + '?';
}

document.addEventListener("DOMContentLoaded", function() {
    Array.prototype.forEach.call(document.getElementsByClassName('delete-link'), (deleteLink) => {
        deleteLink.addEventListener('click', (e) => {
            let modal = document.getElementById('delete-modal');
            document.getElementById('delete-modal-message').innerText = buildMessage(deleteLink)
            modal.showModal();
            modal.classList.remove('hidden');
            document.getElementById('delete-modal-cancel').addEventListener('click', () => {
                modal.close();
                modal.classList.add('hidden');
            });
            document.getElementById('delete-modal-confirm').addEventListener('click', () => {
                modal.close();
                modal.classList.add('hidden');
                let form = deleteLink.parentElement.getElementsByTagName('form')[0];
                form.submit();
            });
        });
    });
});