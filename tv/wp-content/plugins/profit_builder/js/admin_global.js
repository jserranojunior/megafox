(function($){
	$(document).ready(function(){
		if($('#wp-content-editor-container').length > 0) {
			if(typeof pbuilderSwitch == 'undefined') pbuilderSwitch = 'off';
            if(typeof pbuilderEnabled == 'undefined') pbuilderEnabled = 'true';
            
            if(pbuilderEnabled == 'true'){
    			var html = '<a href="#" id="pbuilder_switch" class="wp-switch-editor switch-pbuilder'+(pbuilderSwitch == 'on' ? ' active' : '')+'">Profit Builder</a>';
    			$('#wp-content-editor-tools .wp-editor-tabs').append(html);
    			
    			html = '';
    			html += '<div class="pbuilder_editor_mask'+(pbuilderSwitch == 'on' ? ' active' : '')+'">';
    			html += '<div class="pbuilder_editor_border">';
    			html += '<div class="pbuilder_editor_content">';
    			html += '<div class="pbuilder_editor_buttons">';
    			html += '<a href="'+ajaxurl+'?action=pbuilder_edit&p='+$('#post_ID').val()+'" id="pbuilder_edit_page" class="pbuilder_button_primary">Edit page</a>';
    			html += '<a href="'+ajaxurl+'?action=pbuilder_switch&p='+$('#post_ID').val()+'&sw=off" id="pbuilder_deactivate" class="pbuilder_button">Deactivate</a>';
    			html += '</div>';
    			html += '</div>';
    			html += '</div>';
    			html += '</div>';
    		
    			$('#wp-content-editor-container').append(html);
			}
            
			$('#pbuilder_switch').click(function(e){
				e.preventDefault();
				if(!$(this).hasClass('active')) {
					$('#publishing-action .spinner').show();
					$.get(ajaxurl+'?action=pbuilder_switch&p='+$('#post_ID').val()+'&sw=on', function(response) {
						if(response == 'success') {
							$('#pbuilder_switch').addClass('active').blur();
							$('.pbuilder_editor_mask').addClass('active');
							$('#wp-admin-bar-pbuilder_edit a').attr('href', ajaxurl+'?action=pbuilder_edit&p='+$('#post_ID').val());
							$('#publishing-action .spinner').hide();
						}
						else {
							alert(response);
							$('#publishing-action .spinner').hide();
						}
					});
				}
			});
			
			$('#pbuilder_deactivate').click(function(e){
				e.preventDefault();
				$('#publishing-action .spinner').show();
				$.get($(this).attr('href'), function(response){
					$('#pbuilder_switch').removeClass('active');
					$('.pbuilder_editor_mask').removeClass('active');
					$('#wp-admin-bar-pbuilder_edit a').attr('href', ajaxurl+'?action=pbuilder_edit&p='+$('#post_ID').val()+'&sw=on');
					$('#publishing-action .spinner').hide();
				});
			});
			
			$('#content-tmce, #content-html').click(function(){
				if($('#pbuilder_switch').hasClass('active')) {
					$('#pbuilder_deactivate').trigger('click');
				}
			});
		}
	});
})(jQuery)