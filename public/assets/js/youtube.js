// youtube player
// REQUIRED: Include "jQuery Query Parser" plugin here or before this point: 
//       https://github.com/mattsnider/jquery-plugin-query-parser

$(document).ready(function(){
    
// BOOTSTRAP 3.0 - Open YouTube Video Dynamicaly in Modal Window
// Modal Window for dynamically opening videos
$('a[href^="http://www.youtube.com"]').on('click', function(e){
  // Store the query string variables and values
    // Uses "jQuery Query Parser" plugin, to allow for various URL formats (could have extra parameters)
    var queryString = $(this).attr('href').slice( $(this).attr('href').indexOf('?') + 1);
    var queryVars = $.parseQuery( queryString );

    // if GET variable "v" exists. This is the Youtube Video ID
    if ( 'v' in queryVars )
    {
        // Prevent opening of external page
        e.preventDefault();

        // Calculate default iFrame embed size based on current window size
        // (these will only be used if data attributes are not specified)
        if ($(window).height() < $(window).width()) {
            var vidHeight = $(window).height() * 0.7;
            var vidWidth = vidHeight * 1.77777;
        } else {
            var vidWidth = $(window).width() * 0.9;
            var vidHeight = vidWidth / 1.77777;
        }
        
        if ( $(this).attr('data-width') ) { vidWidth = parseInt($(this).attr('data-width')); }
        if ( $(this).attr('data-height') ) { vidHeight =  parseInt($(this).attr('data-height')); }
        var iFrameCode = '<iframe width="' + vidWidth + '" height="'+ vidHeight +'" scrolling="no" allowtransparency="true" allowfullscreen="true" src="http://www.youtube.com/embed/'+  queryVars['v'] +'?rel=0&wmode=transparent&showinfo=0" frameborder="0"></iframe>';

        // Replace Modal HTML with iFrame Embed
        $('#mediaModal .modal-body').html(iFrameCode);
        // Set new width of modal window, based on dynamic video content
        $('#mediaModal').on('show.bs.modal', function () {
            // Add video width to left and right padding, to get new width of modal window
            var modalBody = $(this).find('.modal-body');
            var modalDialog = $(this).find('.modal-dialog');
            var newModalWidth = vidWidth + parseInt(modalBody.css("padding-left")) + parseInt(modalBody.css("padding-right"));
            newModalWidth += parseInt(modalDialog.css("padding-left")) + parseInt(modalDialog.css("padding-right"));
            newModalWidth += 'px';
            // Set width of modal (Bootstrap 3.0)
            $(this).find('.modal-dialog').css('width', newModalWidth);
        });

        // Open Modal
        $('#mediaModal').modal();
    }
});

// Clear modal contents on close. 
// There was mention of videos that kept playing in the background.
$('#mediaModal').on('hidden.bs.modal', function () {
    $('#mediaModal .modal-body').html('');
});

}); 