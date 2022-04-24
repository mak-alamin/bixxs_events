jQuery(document).ready(function(){
	jQuery(document).on("click", ".deleteRecords", function(){
		if(confirm("Are you sure you want to remove that records?")){			
			return true;
		}
		return false;
	});
});


