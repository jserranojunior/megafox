var deployed = 'no';
var currentLogo = 'div_logo_normal';

var changeColor = function(color, ratio, darker) {
	
	color = color.replace(/^\s*|\s*$/, '');

	color = color.replace(
		/^#?([a-f0-9])([a-f0-9])([a-f0-9])$/i,
		'#$1$1$2$2$3$3'
	);

	var difference = Math.round(ratio * 256) * (darker ? -1 : 1),
		// Determine if input is RGB(A)
		rgb = color.match(new RegExp('^rgba?\\(\\s*' +
			'(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
			'\\s*,\\s*' +
			'(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
			'\\s*,\\s*' +
			'(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
			'(?:\\s*,\\s*' +
			'(0|1|0?\\.\\d+))?' +
			'\\s*\\)$'
		, 'i')),
		alpha = !!rgb && rgb[4] != null ? rgb[4] : null,

		decimal = !!rgb? [rgb[1], rgb[2], rgb[3]] : color.replace(
			/^#?([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i,
			function() {
				return parseInt(arguments[1], 16) + ',' +
					parseInt(arguments[2], 16) + ',' +
					parseInt(arguments[3], 16);
			}
		).split(/,/),
		returnValue;

	return !!rgb ?
		'rgb' + (alpha !== null ? 'a' : '') + '(' +
			Math[darker ? 'max' : 'min'](
				parseInt(decimal[0], 10) + difference, darker ? 0 : 255
			) + ', ' +
			Math[darker ? 'max' : 'min'](
				parseInt(decimal[1], 10) + difference, darker ? 0 : 255
			) + ', ' +
			Math[darker ? 'max' : 'min'](
				parseInt(decimal[2], 10) + difference, darker ? 0 : 255
			) +
			(alpha !== null ? ', ' + alpha : '') +
			')' :

		[
			'#',
			pad(Math[darker ? 'max' : 'min'](
				parseInt(decimal[0], 10) + difference, darker ? 0 : 255
			).toString(16), 2),
			pad(Math[darker ? 'max' : 'min'](
				parseInt(decimal[1], 10) + difference, darker ? 0 : 255
			).toString(16), 2),
			pad(Math[darker ? 'max' : 'min'](
				parseInt(decimal[2], 10) + difference, darker ? 0 : 255
			).toString(16), 2)
		].join('');
};
var lighterColor = function(color, ratio) {
	return changeColor(color, ratio, false);
};
var darkerColor = function(color, ratio) {
	return changeColor(color, ratio, true);
};
var pad = function(num, totalChars) {
	var pad = '0';
	num = num + '';
	while (num.length < totalChars) {
		num = pad + num;
	}
	return num;
};
function rgb2hex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}
(function($){
"use strict";

	$(document).ready(function(){

		$('#pbtheme_settings').find('#width-1200').addClass('activated');
		$('#pbtheme_settings').find('#settings_wide').addClass('activated');

		$(document).on('click', '#show_hide_settings', function(e) {
			e.preventDefault();
			if ( deployed == "no" ){
				$('#pbtheme_settings').animate({'left':0}, 200);
				deployed = "yes";
			}
			else {
				$('#pbtheme_settings').animate({'left':-186}, 200);
				deployed = "no";
			}
		});

		var current_header = 'layout-'+$('#settings-header-layout').children(":selected").text();

		$(document).on('change', '#settings-header-layout', function(e) {

			var current_class = $('.header_wrapper').attr('class');
			var selected_header = $(this).children(":selected").text();
			var class_new = current_class.replace(current_header, 'layout-'+selected_header);
			$('.header_wrapper').attr('class', class_new);

			var current_class_footer = $('.footer_wrapper').attr('class');
			var selected_header_footer = $(this).children(":selected").text();
			var class_new_footer = current_class_footer.replace(current_header, 'layout-'+selected_header);
			$('.footer_wrapper').attr('class', class_new_footer);

			current_header = 'layout-'+selected_header;
			return false;
		});



		$(document).on('click', '#settings_boxed', function() {

			$(this).parent().children().removeClass('activated');
			$(this).addClass('activated');

			var current_width = $('#pbtheme_settings').find('.settings_button_width.activated').text();
			$('#pbtheme_wrapper, .top-separator').css('max-width', current_width+'px');

			if ( $('body').hasClass('pbtheme_wide') ) {
				$('body').removeClass('pbtheme_wide').addClass('pbtheme_boxed');

			}
		});

		$(document).on('click', '#settings_wide', function() {

			$(this).parent().children().removeClass('activated');
			$(this).addClass('activated');
			$('#pbtheme_wrapper, .top-separator').css('max-width', '100%');

			if ( $('body').hasClass('pbtheme_boxed') ) {
				$('body').removeClass('pbtheme_boxed').addClass('pbtheme_wide');

			}
		});

		$(document).on('click', '.settings_button_width', function() {

			$(this).parent().children().removeClass('activated');
			$(this).addClass('activated');

			var width = $(this).text();

			$('.pbtheme_container, .pbtheme_boxed .top-separator, body.pbtheme_boxed #pbtheme_wrapper, .social_header, .header_wrapper nav li.menu-item.has_sidebar ul.navmenu_fullwidth li.sidebar_holder, .pbtheme_maxwidth, .header_wrapper .menu_wrapper, .pbtheme_top').css('max-width', width+'px');
		});

		$(document).on('change', '#settings-headings-font', function() {
			
			var newFont = $(this).find("option:selected").text();

		var style = 'body,li.sidebar_holder{font-family:"PT Sans",serif; font-style:normal}#div_header_menu,#form-wrap >p:first-child,#sendpress_signup #thanks,.bbp-footer,.bbp-forum-title,.bbp-header,.bbp-pagination-count,.bbp-topic-permalink,.pbtheme_header_font,.footer_header .footer_counter,.menu-trigger-icon,.news_feed_tabs .tabsnav a,.shop_table.cart thead,.widget>ul>li,.woocommerce .summary .price,.woocommerce-tabs ul.tabs>li>a,.yop-poll-question,aside .product_list_widget a,aside.widget_display_stats,h1,h2,h3,h4,h5,h6,span.product_hover_text, .frb_scrl_count_digit_wrap, .frb_percentage_chart, p.logged-in-as, p.comment-notes {font-family:"'+newFont+'",serif !important}';

			$('head #pbtheme_font_styles').remove();
			$('head').append('<style id="pbtheme_font_styles" type="text/css">'+style+'</style>');
				
		});

		$(document).on('change', '#settings-background', function() {
			
			var current = $(this).find("option:selected").text();
			var newFile = 'http://www.imsuccesscenter.com/pbtheme/bg/'+current;

			$('body').css({'background-image' : 'url('+newFile+')', 'background-position' : 'center center', 'background-repeat' : 'repeat'});

		});

		$(document).on('change', '#settings-logo', function() {

			var current = $(this).find("option:selected").val();
			$('.header_wrapper').removeClass(currentLogo).addClass(current);
			currentLogo = current;

		});



		$(document).on('click', '.color_pick', function() {

			$(this).parent().children().removeClass('activated');
			$(this).addClass('activated');
			
			var color_name = $(this).attr('data-color');
			var color = rgb2hex($(this).css('background-color'));
			var current_color = rgb2hex($('#div_current_color').css('background-color'));

			//var color = $(this).css('background-color');
			var rgb_current_color = $('#div_current_color').css('background-color');

			var only_rgb = color.substring(4);
			only_rgb = only_rgb.replace(')','');

			$(document).find('style').each(function(){

				var re = new RegExp(current_color, 'g');

				$(this).html($(this).html().replace(re, color));

			});

			$('#pbuilder_content_wrapper *').filter(function() {
				return $(this).css('background-color') == rgb_current_color;
				}
			).css('background-color', color);
			$('#pbuilder_content_wrapper *').filter(function() {
				return $(this).attr('data-backcolor') == current_color;
				}
			).attr('data-backcolor', color);
			$('#pbuilder_content_wrapper *').filter(function() {
				return $(this).attr('data-hoverbackcolor');
				}
			).attr('data-hoverbackcolor', lighterColor(color,0.2));

			$('img.div_mainlogoimg').attr('src', pbthemeService.siteurl+'/settings/images/logo_'+color_name+'.png');
			$('img.div_sticky_logoimg').attr('src', pbthemeService.siteurl+'/settings/images/logo_'+color_name+'_sticky.png');
			$('img.div_footerlogo').attr('src', pbthemeService.siteurl+'/settings/images/logo_'+color_name+'_white.png');
			$('.tp-caption.PBTheme-Price-Small-er').css('background-color', color);

			$('#div_current_color').css('background-color', color);


/*
			$('#pbuilder_content_wrapper *').filter(function() {
				return $(this).css('border-color') == current_color;
				}
			).css('border-color', color);
			$('#pbuilder_content_wrapper *').filter(function() {
				return $(this).css('color') == current_color;
				}
			).css('color', color);
			$('#pbuilder_content_wrapper *').filter(function() {
				return $(this).css('background-color') == current_color;
				}
			).css('background-color', color);
			$('#pbuilder_content_wrapper *').filter(function() {
				return $(this).css('background-color') == current_color;
				}
			).css('background-color', color_light_above);

		var style = '';

			$('#div_current_color').css('background-color', color);
			$('head #pbtheme_styles').remove();
			$('head').append('<style id="pbtheme_styles" type="text/css">'+style+'</style>');*/

		});





	});
})(jQuery);