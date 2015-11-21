// Typeahead JS
$(document).ready(function() {
    $("#search").keyup(function() {
        alert(config);
        $.ajax({
            type: "POST",
            url: "application/search",
            data: {
                keyword: $("#search").val()
            },
            dataType: "json",
            success: function(data) {
                if (data.length > 0) {
                    $('#DropdownExercises').empty();
                    $('#search').attr("data-toggle", "dropdown");
                    $('#DropdownExercises').dropdown('toggle');
                } else if (data.length == 0) {
                    $('#search').attr("data-toggle", "");
                }
                $.each(data, function(key, value) {
                    if (data.length >= 0)
                        $('#DropdownExercises').append('<li role="presentation"><a href="view/exercise/' + value['id'] + '">' + value['name'] + '</a></li>');
                });
            }
        });
    });
    $('ul.txtcountry').on('click', 'li a', function() {
        $('#search').val($(this).text());
        ($(this).getAttribute('href'));
    });
});