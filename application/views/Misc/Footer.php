<!-- Youtube player -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/youtube.js"></script>
<!-- Auto resize input -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.autosize.input.js"></script>
<!-- Open/close content -->
<script>
$('.closeall').click(function(){
  $('.panel-collapse.in')
    .collapse('hide');
});
$('.openall').click(function(){
  $('.panel-collapse:not(".in")')
    .collapse('show');
});
</script>