(function($){

	$(document).on('click', '.sb_sortable_addnew', function(e){
		e.preventDefault();
		var html = '<div class="sb_sortable_item">'+
						'<div class="sb_sortable_handle"></div>'+
						'<div class="sb_icon_holder"><img src="'+$(this).attr('data-sburl')+'images/Style1/Style1_0000_Vector-Smart-Object.png" alt=""/></div>'+
						'<div class="sb_sortable_inputs">'+
							'<div class="sb_sortable_input_holder"><input class="sb_sortable_input sb_sortable_input_url" placeholder="URL" type="text" value=""/></div>'+
							'<div class="sb_sortable_input_holder"><input class="sb_sortable_input sb_sortable_input_title" placeholder="Title" type="text" value=""/></div>'+
						'</div>'+
						'<div class="sb_sortable_delete">x</div>'+
						'<div style="clear: both;"></div>'+
					'</div>';
		var $sort = $(this).closest('.widget-content').find('.sb_sortable');
		$sort.append(html);
		sortableRefresh($sort);
	});
	$(document).on('click', '.sb_icon_holder', function(e){
		e.preventDefault();
		var top = $(this).closest('.sb_sortable_item').position().top+36;
		$(this).closest('.widget-content').find('.sb_icon_picker').data('sb_icon_holder', $(this)).css({top:top, display:'block'});
	});
	$(document).on('click', '.sb_sortable_delete', function(e){
		var $sort = $(this).closest('.sb_sortable');
		$(this).closest('.sb_sortable_item').remove();
		sortableRefresh($sort);
	});
	$(document).on('keyup', '.sb_sortable_input', function(e){
		var $sort = $(this).closest('.sb_sortable');
		sortableRefresh($sort);
	});
	
	$(document).on('mouseenter', '.sb_icon_picker', function(e){
		$(this).data('hover',true);
	});
	$(document).on('mouseleave', '.sb_icon_picker', function(e){
		$(this).data('hover',false);
	});
	$('body').click(function(){
		$('.sb_icon_picker:visible').each(function(){
			if(typeof $(this).data('hover') == 'undefined' || !$(this).data('hover') ) {
				$(this).hide();
			}
		})
	})
	
	$(document).on('change', '.sb_icon_picker select', function(e){
		$(this).parent().find('.sb_icons').hide();
		$(this).parent().find('.sb_icons_'+$(this).val()).show();
	});
	
	$(document).on('click', '.sb_icon_picker img', function(e){
		var $sort = $(this).closest('.sb_sortable');
		$(this).closest('.sb_icon_picker').data('sb_icon_holder').find('img').attr('src', $(this).attr('src'));
		$(this).closest('.sb_icon_picker').hide();
		sortableRefresh($sort);
	});
	
	function sortableRefresh($sort) {
		var data = '';
		$sort.find('.sb_sortable_item').each(function(index){
			if(index != 0) {
				data += '||';
			}
			data += $(this).find('img').attr('src') + '::' + $(this).find('.sb_sortable_input_url').val() + '::' + $(this).find('.sb_sortable_input_title').val();
		});
		$sort.parent().find('.sb_sortable_data').val(data);
	}

	
	
})(jQuery);