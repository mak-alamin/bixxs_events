<script type="text/javascript">
jQuery(document).ready(function( $ ){
$('.wcpa_form_outer').on('input','.prc',function(){
	var totalquan = 0;
	$('.wcpa_form_outer .prc').each(function(){
	var Inputval = $(this).val();
	if($.isNumeric(Inputval)){
	totalquan += parseFloat(Inputval);
	}

	});
  $('#totalquantitytoset').val(totalquan);
  $('input[name="totalquantitytoset"]').each(function() {
    $('input[name="quantity"]').val($(this).val());
});
	
});
    
   
        
    
});
</script>