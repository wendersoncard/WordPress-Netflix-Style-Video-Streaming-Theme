jQuery(document).ready(function($){
	//open/close mega-navigation
	$('.streamium-drop-dropdown-trigger').on('click', function(event){
		event.preventDefault();
		toggleNav();
	});

	$('.streamium-drop-dropdown-content li a').on('click', function(event){
		toggleNav();
	});

	//close meganavigation
	$('.streamium-drop-dropdown .streamium-drop-close').on('click', function(event){
		event.preventDefault();
		toggleNav();
	});

	//on desktop - differentiate between a user trying to hover over a dropdown item vs trying to navigate into a submenu's contents
	var submenuDirection = ( !$('.streamium-drop-dropdown-wrapper').hasClass('open-to-left') ) ? 'right' : 'left';
	$('.streamium-drop-dropdown-content').menuAim({
        activate: function(row) {
        	$(row).children().addClass('is-active').removeClass('fade-out');
        	if( $('.streamium-drop-dropdown-content .fade-in').length == 0 ) $(row).children('ul').addClass('fade-in');
        },
        deactivate: function(row) {
        	$(row).children().removeClass('is-active');
        	if( $('li.has-children:hover').length == 0 || $('li.has-children:hover').is($(row)) ) {
        		$('.streamium-drop-dropdown-content').find('.fade-in').removeClass('fade-in');
        		$(row).children('ul').addClass('fade-out')
        	}
        },
        exitMenu: function() {
        	$('.streamium-drop-dropdown-content').find('.is-active').removeClass('is-active');
        	return true;
        },
        submenuDirection: submenuDirection,
    });

	function toggleNav(){
		var navIsVisible = ( !$('.streamium-drop-dropdown').hasClass('dropdown-is-active') ) ? true : false;
		$('.streamium-drop-dropdown').toggleClass('dropdown-is-active', navIsVisible);
		$('.streamium-drop-dropdown-trigger').toggleClass('dropdown-is-active', navIsVisible);
		if( !navIsVisible ) {
			$('.streamium-drop-dropdown').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',function(){
				$('.move-out').removeClass('move-out');
				$('.is-active').removeClass('is-active');
			});	
		}
	}
	
});