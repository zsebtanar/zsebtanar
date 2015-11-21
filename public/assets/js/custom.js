$(function(){
    $("#keyword").autocomplete({
        source: <?php echo json_encode(base_url()); ?>+"/zsebtanar4/public/application/search" // path to the get_birds method
    });
});