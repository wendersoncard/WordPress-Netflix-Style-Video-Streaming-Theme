jQuery(document).ready(function($){

	//if you change this breakpoint in the style.css file (or _layout.scss if you use SASS), don't forget to update this value as well
	var MqL = 1170;
	//move nav element position according to window width
	moveNavigation();
	$(window).on('resize', function(){
		(!window.requestAnimationFrame) ? setTimeout(moveNavigation, 300) : window.requestAnimationFrame(moveNavigation);
	});

	//$('.sub-menu').addClass('is-hidden');
	$('.menu-item .sub-menu').prepend('<li class="go-back"><a href="#0">Menu</a></li><li class="see-all"><a href="/">Home</a></li>');
 
	//mobile - open lateral menu clicking on the menu icon
	$('.cd-nav-trigger').on('click', function(event){
		event.preventDefault();

		if( $('.cd-main-content').hasClass('nav-is-visible') ) {		

			$('.cd-primary-nav').animate({
				opacity: 0
			}, 50);
 
			$('.cd-main-header, .cd-main-content').animate({
				right: '0px'
			}, 150).promise().done(function () {
				
				$('.cd-overlay').remove();
			    closeNav();

			});

		} else {

			$('body').addClass('overflow-hidden');

			// Only ad overlay if it exists
			if (!$(".cd-main-content .cd-overlay").length > 0){ 
				$('.cd-main-content').prepend('<div class="cd-overlay is-visible"></div>');
			}
			$(this).addClass('nav-is-visible');
			$('.cd-primary-nav').animate({
				opacity: 1
			}, 50);

			$('.cd-main-header, .cd-main-content').animate({
				right: (window.innerWidth-50) + 'px'
			}, 150).promise().done(function () {

			    $('.cd-primary-nav').addClass('nav-is-visible');
			    $('.cd-main-content').addClass('nav-is-visible');
			    $('.cd-search').removeClass('is-visible');
				$('.cd-search-trigger').removeClass('search-is-visible');

			});

		}
	});

	//open search form
	$('.cd-search-trigger').on('click', function(event){
		
		event.preventDefault();
		toggleSearch();
		closeNav();
	
	});

	//close lateral menu on mobile 
	$('.cd-overlay').on('swiperight', function(){
		if($('.cd-primary-nav').hasClass('nav-is-visible')) {
			closeNav();
			$('.cd-overlay').removeClass('is-visible');
		}
	});
	$('.nav-on-left .cd-overlay').on('swipeleft', function(){
		if($('.cd-primary-nav').hasClass('nav-is-visible')) {
			closeNav();
			$('.cd-overlay').removeClass('is-visible');
		}
	});

	//prevent default clicking on direct children of .cd-primary-nav 
	$('.cd-primary-nav').children('.menu-item-has-children').children('a').on('click', function(event){
		event.preventDefault();
	});
	//open submenu
	$('.menu-item-has-children').children('a').on('click', function(event){

		if( !checkWindowWidth() ) event.preventDefault();
		var selected = $(this);
		var addHeight = selected.parent().attr("id");
		if( selected.next('ul').hasClass('is-hidden') ) {
			//desktop version only
			selected.addClass('selected').next('ul').removeClass('is-hidden').end().parent('.menu-item-has-children').parent('ul').addClass('moves-out');
			//var numberLi = $("#" + addHeight + " ul").children().length;
			//$("#" + addHeight).animate({height:numberLi*30},200);
			selected.parent('.menu-item-has-children').siblings('.menu-item-has-children').children('ul').addClass('is-hidden').end().children('a').removeClass('selected');

			// Only ad overlay if it exists
			if (!$(".cd-main-content .cd-overlay").length > 0){ 
				$('.cd-main-content').prepend('<div class="cd-overlay is-visible"></div>');
			}
			
		} else {

			selected.removeClass('selected').next('ul').addClass('is-hidden').end().parent('.menu-item-has-children').parent('ul').removeClass('moves-out');
			$('.cd-overlay').remove();

		}
		
		// Close search
		$('.cd-search').removeClass('is-visible');
		$('.cd-search-trigger').removeClass('search-is-visible');

	});

	//submenu items - go back link
	$('.go-back').on('click', function(){
		var selected = $(this);
		var addHeight = selected.parents('li:eq(0)').attr('id');
		$("#" + addHeight).css("height","auto");
		$(this).parent('ul').addClass('is-hidden').parent('.menu-item-has-children').parent('ul').removeClass('moves-out');
	});

	function closeNav() {	

		$('.cd-nav-trigger').removeClass('nav-is-visible');
		$('.cd-main-header').removeClass('nav-is-visible');
		$('.cd-primary-nav').removeClass('nav-is-visible');
		$('.menu-item-has-children ul').addClass('is-hidden');
		$('.menu-item-has-children a').removeClass('selected');
		$('.moves-out').removeClass('moves-out');
		$('.cd-main-content').removeClass('nav-is-visible');
		$('body').removeClass('overflow-hidden');

	}

	function toggleSearch(type) {

		if(type === "close") {
			
			$('.cd-search').removeClass('is-visible');
			$('.cd-search-trigger').removeClass('search-is-visible');
			$('.cd-overlay').remove();

		} else {

			// Only ad overlay if it exists
			if (!$(".cd-main-content .cd-overlay").length > 0){ 
				$('.cd-main-content').prepend('<div class="cd-overlay is-visible"></div>');
			}

			$('.cd-search').toggleClass('is-visible');
			$('.cd-search-trigger').toggleClass('search-is-visible');
			if($(window).width() > MqL && $('.cd-search').hasClass('is-visible')) $('.cd-search').find('input[type="search"]').focus();
			($('.cd-search').hasClass('is-visible')) ? $('.cd-overlay').addClass('is-visible') : $('.cd-overlay').removeClass('is-visible') ;
		
		}

	}

	function checkWindowWidth() {
		var e = window, 
            a = 'inner';
        if (!('innerWidth' in window )) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        if ( e[ a+'Width' ] >= MqL ) {
			return true;
		} else {
			return false;
		}
	}

	function moveNavigation(){

		var navigation = $('.cd-nav');
  		var desktop = checkWindowWidth();
        if ( !desktop ) {
			navigation.detach();
			navigation.insertAfter('.cd-main-content');
			$('#cd-primary-nav').css('width','100%');
		}else{
			navigation.detach();
			navigation.appendTo('.cd-main-header');
		}
	}

});