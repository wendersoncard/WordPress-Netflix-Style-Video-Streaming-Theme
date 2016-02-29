jQuery(document).ready(function($){

	//open the testimonials modal page
	$('.cd-see-all').on('click', function(){

		$(".cd-testimonials-all-wrapper").empty();
		$.ajax({
            type: 'POST',
            dataType: 'html',
            url: streamium_object.ajax_url,
            data: {
                'action': 'streamium_user_reviews',
                'pid': $(this).data("pid"),
            },
            success: function(data) {

            	console.log(data);
            	$(".cd-testimonials-all-wrapper").html(data);
				$('.cd-testimonials-all-wrapper').children('ul').masonry({
			  		itemSelector: '.cd-testimonials-item'
				});
            	$('.cd-testimonials-all').addClass('is-visible');

            }
        });

		
	});

	//close the testimonials modal page
	$('.cd-testimonials-all .close-btn').on('click', function(){
		$('.cd-testimonials-all').removeClass('is-visible');
	});
	$(document).keyup(function(event){
		//check if user has pressed 'Esc'
    	if(event.which=='27'){
    		$('.cd-testimonials-all').removeClass('is-visible');	
	    }
    });
    
});