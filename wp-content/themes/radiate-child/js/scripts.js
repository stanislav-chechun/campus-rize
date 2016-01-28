jQuery( document ).ready(function() {

    jQuery(window).scroll(function(){
	    if (jQuery(window).scrollTop() > 150) {
	        jQuery('nav').addClass('scroll');
	        jQuery('#logo .wsite-logo img').addClass('scroll-img');
	    }
	    else {
	        jQuery('nav').removeClass('scroll');
	        jQuery('#logo .wsite-logo img').removeClass('scroll-img');
	    }
	});

	var $content = jQuery('#main-content');
	jQuery("#navbar-toggle-btn").click(function() {
		jQuery("#navmobile").toggle();
		$content.toggleClass('isOut');
		var isOut = $content.hasClass('isOut');
		$content.animate({marginLeft: isOut ? '215px' : '0'}, 50);
	});

	jQuery(window).on('resize load', function(){

		win_w = jQuery(window).width();
		
		if(win_w > 751) {
	        var maxHeight = 0;
			jQuery(".posts-stages").each(function(){
				if ( jQuery(this).height() > maxHeight ) {
				  	maxHeight = jQuery(this).height();
				}
			});
			jQuery(".posts-stages").height(maxHeight);
	    }
	    else {
	    	jQuery(".posts-stages").height('auto');
	    }

	});

	jQuery(".wpcf7-list-item input").wrap( "<label></label>" ).after("<span></span>");

	jQuery("input[type='submit']").on('click',function(){

   		jQuery("input[type='text']").each(function(){   	
			if ( jQuery(this).val() == 0) {
				jQuery(this).addClass('empty-field');
			}
			else {
				jQuery(this).removeClass('empty-field');
			}

			jQuery(this).on('change', function(){
				jQuery(this).removeClass('empty-field');
			});

		});	

		jQuery("input[type='email']").each(function checkEmail(){ 

			var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
			
			if(!pattern.test(jQuery(this).val())){
		        jQuery(this).addClass('empty-field');
		    } else {
		        jQuery(this).removeClass('empty-field');
		    }  

			jQuery(this).on('change', checkEmail);

		});	
		
		jQuery("input[type='checkbox'], input[type='radio']").next().addClass("not-checked");

		jQuery(".not-checked").on('click',function(){
		    checked = jQuery(this).prev().attr("name");
			jQuery("input[name^='" + checked + "']").next().each(function(){
				jQuery(this).removeClass('not-checked');
			});	            
		});
		
		jQuery("input[type='checkbox'], input[type='radio']").each(function(){		

			if(jQuery(this).attr("checked") == "checked") { 
				checked = jQuery(this).attr("name");
				jQuery("input[name^='" + checked + "']").next().each(function(){
					jQuery(this).removeClass("not-checked");
				});	            
		    }		   

		});
			
 	});

	jQuery("#lk-menu span.rcl-tab-button a").click(function(){
       jQuery("#chat-inner-tabs").css("display", "none");
    });

	//jQuery("#tab-button-chat a").click(function(){
       //jQuery("#chat-inner-tabs").css("display", "block");
    //});

	//jQuery("#chat-inner-tabs a").click(function(){
       //jQuery("#tab-button-chat a").addClass("active");
    //});


    jQuery("#tab-button-privat a").click(function(){
       jQuery("#chat-inner-tabs").css("display", "block");
    });

    jQuery("#chat-inner-tabs a").click(function(){
       jQuery("#lk-menu span:nth-child(2) a").addClass("active");
    });

	
	//jQuery("#carousel-wishes div.item:first-child").addClass("active");
	
	jQuery(".owl-carousel").owlCarousel({
		autoPlay : true,		
		items : 4,
		stopOnHover : true,
	});

});