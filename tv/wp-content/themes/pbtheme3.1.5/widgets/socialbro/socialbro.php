<?php

/* Social bro folder url */
define('SOCIAL_BRO_URL', get_template_directory_uri().'/widgets/socialbro/');

/* Load widget with widget_init function */
add_action( 'widgets_init', 'socialbro_widget_init' );
add_action( 'admin_enqueue_scripts', 'socialbro_admin_includes');
add_action( 'wp_enqueue_scripts', 'socialbro_frontend_includes');

function socialbro_admin_includes() {
	wp_enqueue_style('socialbro-admin', SOCIAL_BRO_URL .'socialbro_admin.css');
	wp_enqueue_script('socialbro-admin', SOCIAL_BRO_URL .'socialbro_admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-sortable'), '1.0', true);
}

function socialbro_frontend_includes() {
	wp_enqueue_style('socialbro', SOCIAL_BRO_URL .'socialbro.css');
	wp_enqueue_script('socialbro', SOCIAL_BRO_URL .'socialbro.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-sortable'), '1.0', true);

}

function socialbro_widget_init() {
	register_widget('socialbro');
}

class socialbro extends WP_Widget {
	
   function socialbro() {
   		/* Widget settings. */
  	   $widget_ops = array('description' => 'Multipurpose social icons' );
	   /* Create the widget. */
	   parent::__construct('socialbro', __('+ PBTheme Social Icons', 'pbtheme'), $widget_ops);  
	   
   }
	
	
	/** Widget display template */
	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters('widget_title', (isset($instance['title']) ? $instance['title'] : ''));
		$opacity = (int)(isset($instance['opacity']) ? esc_attr($instance['opacity']) : 100);
		$hover_opacity = (int)(isset($instance['hover_opacity']) ? esc_attr($instance['hover_opacity']) : 100);
		$icons = (isset($instance['icons']) ? esc_attr($instance['icons']) : ''); 
		
		
		//$number = $instance['number'];

		
		/* Widget template */
		
		echo $before_widget;
		
		if ( ! empty( $title ) )
			echo $before_title.$title.$after_title;
		
		$randId = 'social-Bro-wrapper'.rand();
		?>
		
		<style>
			#<?php echo $randId; ?> img {
				opacity: <?php echo $opacity/100; ?>;
				filter:alpha(opacity=<?php echo $opacity; ?>);
			}
			#<?php echo $randId; ?> img:hover {
				opacity: <?php echo $hover_opacity/100; ?>;
				filter:alpha(opacity=<?php echo $hover_opacity; ?>);
			}
			
		</style>
		
		<div id="<?php echo $randId; ?>" class="social-Bro-wrapper">
			<div class="social-Bro-inner">
				<ul class="social-Bro">
				
				<?php
				$iconsExp = explode('||',$icons);
				if($icons != '' && is_array($iconsExp)) {
					foreach($iconsExp as $item) {
						$item = explode('::', $item); 
						?>
				
					<li><a data-hover="<?php echo (isset($item[2]) ? $item[2] : '') ?>" href="<?php echo (isset($item[1]) ? $item[1] : '') ?>"><img src="<?php echo (isset($item[0]) ? $item[0] : '') ?>" alt="" /></a></li>
				<?php
					}
				}
				?>
				
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<?php
		
		echo $after_widget;
		
	}
	
	/* Update widget settings. */
	function update($new_instance, $old_instance) {                
       return $new_instance;
   }


	/* Admin panel form  */
	function form($instance) {
		$title = (isset($instance['title']) ? esc_attr($instance['title']) : '');
		$opacity = (isset($instance['opacity']) ? esc_attr($instance['opacity']) : 100);
		$hover_opacity = (isset($instance['hover_opacity']) ? esc_attr($instance['hover_opacity']) : 100);
		$icons = (isset($instance['icons']) ? esc_attr($instance['icons']) : ''); ?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		
		</p> 
		<p>
		<label for="<?php echo $this->get_field_id('opacity'); ?>">Icon opacity:</label>
		<input id="<?php echo $this->get_field_id('opacity'); ?>" name="<?php echo $this->get_field_name('opacity'); ?>" type="text" value="<?php echo $opacity; ?>" size="3" /> %
		
		</p> 
		<p>
		<label for="<?php echo $this->get_field_id('hover_opacity'); ?>">Icon hover opacity:</label>
		<input id="<?php echo $this->get_field_id('hover_opacity'); ?>" name="<?php echo $this->get_field_name('hover_opacity'); ?>" type="text" value="<?php echo $hover_opacity; ?>" size="3" /> %
		
		</p> 

		<p>
		<label for="<?php echo $this->get_field_id('icons'); ?>">Icons: <a href="#" class="sb_sortable_addnew" data-sburl="<?php echo SOCIAL_BRO_URL; ?>">Add new +</a></label>
		<input class="sb_sortable_data" id="<?php echo $this->get_field_id('icons'); ?>" name="<?php echo $this->get_field_name('icons'); ?>" type="hidden" value="<?php echo $icons; ?>" autocomplete="off" />
		
		<div class="sb_sortable">
		<?php 
		$iconsExp = explode('||',$icons);
		if($icons != '' && is_array($iconsExp)) {
			foreach($iconsExp as $item) {
				$item = explode('::', $item);
		?>
		
			<div class="sb_sortable_item">
				<div class="sb_sortable_handle"></div>
				<div class="sb_icon_holder"><img src="<?php echo $item[0]; ?>" alt=""/></div>
				<div class="sb_sortable_inputs">
					<div class="sb_sortable_input_holder"><input class="sb_sortable_input sb_sortable_input_url" placeholder="URL" type="text" value="<?php echo $item[1]; ?>" autocomplete="off" /></div>
					<div class="sb_sortable_input_holder"><input class="sb_sortable_input sb_sortable_input_title" placeholder="Title" type="text" value="<?php echo $item[2]; ?>" autocomplete="off" /></div>
				</div>
				<div class="sb_sortable_delete">x</div>
				<div style="clear: both;"></div>
			</div>
		<?php
			}
		}
		?>
			<div class="sb_icon_picker">
				<select>
					<option value="style1">Style 1</option>
					<option value="style2">Style 2</option>
					<option value="style3">Style 3</option>
					<option value="style4">Style 4</option>
					<option value="style5">Style 5</option>
					<option value="style6">Style 6</option>
				</select>
				<div class="sb_icons sb_icons_style1" style="display: block">
				<?php
				for($i=0; $i<=69; $i++) {
					$nul = '';
					if($i<10) {
						$nul = '0';
					}
					echo '<img src="'.SOCIAL_BRO_URL.'images/Style1/Style1_00'.$nul.$i.'_Vector-Smart-Object.png" />';
				}
				?>
					<div style="clear:both;"></div>
				</div>
				<div class="sb_icons sb_icons_style2">
				<?php
				for($i=0; $i<=69; $i++) {
					$nul = '';
					if($i<10) {
						$nul = '0';
					}
					echo '<img src="'.SOCIAL_BRO_URL.'images/Style2/Style2_00'.$nul.$i.'_Vector-Smart-Object.png" />';
				}
				?>
					<div style="clear:both;"></div>
				</div>
				<div class="sb_icons sb_icons_style3">
				<?php
				for($i=0; $i<=69; $i++) {
					$nul = '';
					if($i<10) {
						$nul = '0';
					}
					echo '<img src="'.SOCIAL_BRO_URL.'images/Style3/Style3_00'.$nul.$i.'_Vector-Smart-Object.png" />';
				}
				?>
					<div style="clear:both;"></div>
				</div>
				<div class="sb_icons sb_icons_style4">
				<?php
				for($i=0; $i<=69; $i++) {
					$nul = '';
					if($i<10) {
						$nul = '0';
					}
					echo '<img src="'.SOCIAL_BRO_URL.'images/Style4/Style4_00'.$nul.$i.'_Vector-Smart-Object.png" />';
				}
				?>
					<div style="clear:both;"></div>
				</div>
				<div class="sb_icons sb_icons_style5">
				<?php
				for($i=0; $i<=69; $i++) {
					$nul = '';
					if($i<10) {
						$nul = '0';
					}
					echo '<img src="'.SOCIAL_BRO_URL.'images/Style5/Style5_00'.$nul.$i.'_Vector-Smart-Object.png" />';
				}
				?>
					<div style="clear:both;"></div>
				</div>
				<div class="sb_icons sb_icons_style6">
				<?php
				for($i=0; $i<=69; $i++) {
					$nul = '';
					if($i<10) {
						$nul = '0';
					}
					echo '<img src="'.SOCIAL_BRO_URL.'images/Style6/Style6_00'.$nul.$i.'_Vector-Smart-Object.png" />';
				}
				?>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		</p> 
		
		<script>
		(function($){
		if(typeof $('.sb_sortable').sortable != 'undefined') {
			init();
		}
		else {
			$(document).ready(function(){
				init();	
			});
		}
		function init(){
			$('.sb_sortable').each(function(){
				var $this = $(this);
				$this.sortable({
					items : "> .sb_sortable_item",
					cursor: 'move',
					stop : function(event,ui) {
						sortableRefresh($this);
					}
					
				});
			});
		}
		
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
			
		</script>
<?php
	}
}

?>