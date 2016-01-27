jQuery(document).ready( function() {			
	
	jQuery("button.wish_id").click( function() {
		var data = jQuery(this).val();
		
		jQuery.ajax({
			type: 'POST',
			url: wpurl+'/wp-content/plugins/wp-recall/add-on/edit-wish-tab/edit_ajax.php',
			data: "wish_id="+data,
			cache: false,
			success: function(data){
					jQuery("#results").empty();
					jQuery("#results").html(data);
			}
		});
	});
	
});
	