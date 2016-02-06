<!-- Youtube player -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/youtube.js"></script>
<!-- Auto resize input -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.autosize.input.js"></script>
<!-- Open/close content -->
<script>
$('.closeall').click(function(){
  $('.panel-collapse')
    .collapse('hide');
});
$('.openall').click(function(){
  $('.panel-collapse')
    .collapse('show');
});
$('.bs-docs-popover').popover();
$('.bs-docs-popover').on('shown.bs.popover', function () {
	MathJax.Hub.Queue(["Typeset",MathJax.Hub,".bs-docs-popover"]);
})
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>