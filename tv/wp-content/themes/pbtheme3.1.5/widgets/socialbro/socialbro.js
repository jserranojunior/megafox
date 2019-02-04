(function($){	
	$(document).ready(function(){
		$('.social-Bro').find('li').each(function(){
			var hoverText = $(this).find('a').attr('data-hover');
			if(hoverText != '') {
				$(this).append('<div class="social-Bro-tooltip"><div class="social-Bro-tooltip-text"></div><div class="social-Bro-triangle"></div></div>');
				$(this).find('.social-Bro-tooltip-text').append(hoverText);
				var tooltipLeftOffset = $(this).find('.social-Bro-tooltip').outerWidth()/2;
				$(this).find('.social-Bro-tooltip').css({marginLeft : -tooltipLeftOffset});
			}
		});
	});
})(jQuery);