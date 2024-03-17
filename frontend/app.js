$(document).ready(function() {
    function fetchNews() {
        $.ajax({
            url: 'http://localhost/news/backend/api.php', 
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.status === 'Success') {
                    $('#newsList').empty();
                    $.each(data.News, function(i, news) {
                        $('#newsList').append(
                            '<div class="news-item">' +
                            '<h3>' + news.text + '</h3>' +
                            '<button onclick="deleteNews(' + news.id + ')">Delete</button>' +
                            '</div>'
                        );
                    });
                } else {
                    $('#newsList').html('<p>No news available.</p>');
                }
            }
        });
    }

    fetchNews();

    $('#addNewsForm').submit(function(e) {
        e.preventDefault();
        var title = $('#title').val();
        var content = $('#content').val();
        $.ajax({
            url: 'http://localhost/news/backend/api.php', 
            method: 'POST',
            data: { text: title + ' - ' + content },
            dataType: 'json',
            success: function(data) {
                if(data.status === 'Success') {
                    fetchNews(); 
                    $('#title').val('');
                    $('#content').val('');
                } else {
                    alert('Error adding news.');
                }
            }
        });
    });

    window.deleteNews = function(id) {
        $.ajax({
            url: 'http://localhost/news/backend/api?id=' + id, 
            method: 'DELETE',
            dataType: 'json',
            success: function(data) {
                if(data.status === 'Success') {
                    fetchNews(); 
                } else {
                    alert('Error deleting news.');
                }
            }
        });
    };
});
