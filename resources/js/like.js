document.addEventListener('DOMContentLoaded', function() {
    // Ambil semua tombol like
    const likeButtons = document.querySelectorAll('.like-button');

    // Tambahkan event listener untuk setiap tombol
    likeButtons.forEach(button => {
        button.addEventListener('click', handleLikeClick);
    });

    function handleLikeClick(event) {
        const button = event.currentTarget;
        const destinationId = button.dataset.destinationId;
        const isLiked = button.dataset.isLiked === 'true';
        const likeUrl = button.dataset.likeUrl;
        const likeIcon = button.querySelector('.like-icon');
        let likesCountElement = button.querySelector('.likes-count');
        let likesCount = parseInt(button.dataset.likesCount);

        // Buat token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Buat FormData
        const formData = new FormData();
        formData.append('like', isLiked ? '' : '1');
        formData.append('_token', csrfToken);

        // Ubah tampilan secara optimistic
        if (isLiked) {
            // Unlike
            likeIcon.classList.remove('text-red-500', 'fill-red-500');
            likeIcon.classList.add('text-gray-400');
            likeIcon.setAttribute('fill', 'none');
            likesCount--;
        } else {
            // Like
            likeIcon.classList.add('text-red-500', 'fill-red-500');
            likeIcon.classList.remove('text-gray-400');
            likeIcon.setAttribute('fill', 'currentColor');
            likesCount++;
        }

        // Update jumlah like
        if (likesCount > 0) {
            // Jika ada likes, update atau buat elemen count
            if (!likesCountElement) {
                // Buat elemen jika belum ada
                likesCountElement = document.createElement('span');
                likesCountElement.className = 'text-xs font-medium ml-1 text-gray-500 likes-count';
                button.appendChild(likesCountElement);
            }
            likesCountElement.textContent = likesCount;
        } else if (likesCountElement) {
            // Jika likes = 0 dan elemen ada, hapus elemen
            likesCountElement.remove();
        }

        // Update data attribute
        button.dataset.isLiked = isLiked ? 'false' : 'true';
        button.dataset.likesCount = likesCount;

        // Kirim permintaan AJAX
        fetch(likeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Handle response if needed
            console.log('Like/unlike successful:', data);
        })
        .catch(error => {
            console.error('Error in like/unlike:', error);
            // Rollback jika terjadi kesalahan
            button.dataset.isLiked = isLiked ? 'true' : 'false';
            button.dataset.likesCount = isLiked ? likesCount + 1 : likesCount - 1;
            likesCount = parseInt(button.dataset.likesCount);

            // Rollback tampilan
            if (isLiked) {
                // Rollback to liked state
                likeIcon.classList.add('text-red-500', 'fill-red-500');
                likeIcon.classList.remove('text-gray-400');
                likeIcon.setAttribute('fill', 'currentColor');
            } else {
                // Rollback to unliked state
                likeIcon.classList.remove('text-red-500', 'fill-red-500');
                likeIcon.classList.add('text-gray-400');
                likeIcon.setAttribute('fill', 'none');
            }

            // Rollback jumlah like
            if (likesCount > 0) {
                if (!likesCountElement) {
                    likesCountElement = document.createElement('span');
                    likesCountElement.className = 'text-xs font-medium ml-1 text-gray-500 likes-count';
                    button.appendChild(likesCountElement);
                }
                likesCountElement.textContent = likesCount;
            } else if (likesCountElement) {
                likesCountElement.remove();
            }
        });
    }
});
