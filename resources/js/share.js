function shareDestination(title, url) {
    if (navigator.share) {
        navigator.share({ title, url })
            .catch(error => console.log('Error sharing:', error));
    } else {
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = url;
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('Link berhasil disalin!');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.share-button').forEach(btn => {
        btn.addEventListener('click', () => {
            const title = btn.dataset.title;
            const url = btn.dataset.url;
            shareDestination(title, url);
        });
    });
});
