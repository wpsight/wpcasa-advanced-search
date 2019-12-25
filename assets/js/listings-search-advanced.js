(function($) {
	
	if($.cookie(wpsight_localize.cookie_search_advanced) != 'closed') {
		$('.listings-search-advanced.open').show();
	}
	
	if ($.cookie(wpsight_localize.cookie_search_advanced) && $.cookie(wpsight_localize.cookie_search_advanced) == 'open') {
	    $('.listings-search-advanced').show();
	    $('.listings-search-advanced-toggle').addClass('open');
	}
	
	$('.listings-search-advanced-toggle').click(function () {
	    if ($('.listings-search-advanced').is(':visible')) {
	    	$.cookie(wpsight_localize.cookie_search_advanced, 'closed',{ expires: 60, path: wpsight_localize.cookie_path });
	        $('.listings-search-advanced .listings-search-field').animate(
	            {
	                opacity: '0'
	            },
	            150,
	            function(){           	
	                $('.listings-search-advanced-toggle').removeClass('open');
	                $('.listings-search-advanced').slideUp(150);	 
	            	$('.listings-search-advanced option:selected').removeAttr('selected');
	            	$('.listings-search-advanced input').attr('checked', false);
	            	$('.listings-search-advanced input').val('');
	            }
	        );
	    }
	    else {
	        $('.listings-search-advanced').slideDown(150, function(){
	        	$.cookie(wpsight_localize.cookie_search_advanced, 'open',{ expires: 60, path: wpsight_localize.cookie_path });
	            $('.listings-search-advanced div').animate(
	                {
	                    opacity: '1'
	                },
	                150
	            );	            
	    		$('.listings-search-advanced-toggle').addClass('open');
	        });
	    }   
	});
	
}(jQuery));