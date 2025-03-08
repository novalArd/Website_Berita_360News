$(document).ready(function () {
    $('#like-button').click(function (e) {
        e.preventDefault();

        const articleId = $(this).data('id');

        $.ajax({
            url: 'like_artikel_ajax.php',
            method: 'POST',
            data: { id: articleId },
            success: function (response) {
                if (response.success) {
                    $('#like-count').text(response.likes);
                } else {
                    alert(response.message); // Tampilkan pesan kesalahan
                }
            },
            error: function (xhr, status, error) {
                alert('Terjadi kesalahan: ' + xhr.status + ' - ' + error);
            }
        });
    });
});
