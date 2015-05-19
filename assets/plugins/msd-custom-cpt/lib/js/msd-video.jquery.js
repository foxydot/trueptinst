jQuery(document).ready(function($) {	
	$('.video-popup-slider .nav li a').click(function(){
		var target = $(this).attr('title');
		var container = $(this).parents('.video-grid');
		var src = $(container).find('.carousel-inner #'+target+' img[lazy-src]').attr('lazy-src');
		$(container).find('.carousel-inner .active').removeClass('active');
		$(container).find('.carousel-inner #'+target+' img[lazy-src]').attr('src',src)
		$(container).find('.carousel-inner #'+target).addClass('active');
	});	
});