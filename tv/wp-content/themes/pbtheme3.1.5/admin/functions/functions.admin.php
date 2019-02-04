<?php
/**
 * SMOF Admin
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.4.0
 * @author      Syamil MJ
 */
/**
 * Head Hook
 *
 * @since 1.0.0
 */
function of_head() { do_action( 'of_head' ); }
/**
 * Add default options upon activation else DB does not exist
 *
 * @since 1.0.0
 */
function of_option_setup()
{
	global $of_options, $options_machine;
	$options_machine = new Options_Machine($of_options);
	if (!of_get_options())
	{
		of_save_options($options_machine->Defaults);
	}
}
/**
 * Change activation message
 *
 * @since 1.0.0
 */
function optionsframework_admin_message() { 
	if(get_option("pbtheme_skiped", "true") != "true"){
	//Tweaked the message on theme activate
?>
	<script type="text/javascript">
	jQuery(function(){
		var message = '<p>This theme comes with an <a href="<?php echo admin_url('admin.php?page=pbtheme_options'); ?>">options panel</a> to configure settings. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';
		jQuery('.themes-php #message2').html(message);
	});
	</script>
<?php
	}
}
/**
 * Get header classes
 *
 * @since 1.0.0
 */
function of_get_header_classes_array() 
{
	global $of_options;
	foreach ($of_options as $value) 
	{
		if ($value['type'] == 'heading')
			$hooks[] = str_replace(' ','',strtolower($value['name']));
	}
	return $hooks;
}
/**
 * Get options from the database and process them with the load filter hook.
 *
 * @author Jonah Dahlquist
 * @since 1.4.0
 * @return array
 */
function of_get_options($key = null, $data = null) {
	do_action('of_get_options_before', array(
		'key'=>$key, 'data'=>$data
	));
	if ($key != null) { // Get one specific value
		$data = get_theme_mod($key, $data);
	} else { // Get all values
		$data = get_theme_mods();
	}
	$data = apply_filters('of_options_after_load', $data);
	do_action('of_option_setup_before', array(
		'key'=>$key, 'data'=>$data
	));
	return $data;
}
/**
 * Save options to the database after processing them
 *
 * @param $data Options array to save
 * @author Jonah Dahlquist
 * @since 1.4.0
 * @uses update_option()
 * @return void
 */
function of_save_options($data, $key = null) {
	global $smof_data;
	if (empty($data))
		return;
	do_action('of_save_options_before', array(
		'key'=>$key, 'data'=>$data
	));
	$data = apply_filters('of_options_before_save', $data);
	if ($key != null) { // Update one specific value
		if ($key == BACKUPS) {
			unset($data['smof_init']); // Don't want to change this.
		}
		set_theme_mod($key, $data);
	} else { // Update all values in $data
		foreach ( $data as $k=>$v ) {
			if (!isset($smof_data[$k]) || $smof_data[$k] != $v) { // Only write to the DB when we need to
				set_theme_mod($k, $v);
			}
		}
	}
	do_action('of_save_options_after', array(
		'key'=>$key, 'data'=>$data
	));
}

function of_options_after_load_missing($data){
    if($data && is_array($data)){
        if(!isset($data['woo_title_height']))
            $data['woo_title_height'] = 60;
        
		if(!isset($data['woo_disable_carticon']))
            $data['woo_disable_carticon'] = 0;
        
		if(!isset($data['woo_force_imageflip']))
            $data['woo_force_imageflip'] = 0;	  
		   
		if(!isset($data['woo_disable_catcount']))
            $data['woo_disable_catcount'] = 0;        		   
		   
        if(!isset($data['woo_enable_fb']))
            $data['woo_enable_fb'] = 0;
            
        if(!isset($data['woo_enable_tw']))
            $data['woo_enable_tw'] = 0;
            
        if(!isset($data['woo_enable_pin']))
            $data['woo_enable_pin'] = 0;
			
		if(!isset($data['woo_enable_gplus']))
            $data['woo_enable_gplus'] = 0;	
            
        if(!isset($data['woo_enable_email']))
            $data['woo_enable_email'] = 0;
            
        if(!isset($data['transposh_enable']))
            $data['transposh_enable'] = 0;
			
		if(!isset($data['disable-top-header']))
            $data['disable-top-header'] = 0;
			
		if(!isset($data['theme_color_top_header']))
            $data['theme_color_top_header'] = '#ffffff';
			
		if(!isset($data['theme_color_header']))
            $data['theme_color_header'] = '#ffffff';
				
    }
    
    return $data;
}
add_filter('of_options_after_load', 'of_options_after_load_missing');
/**
 * For use in themes
 *
 * @since forever
 */
$data = of_get_options();
$pbtheme_data = of_get_options();
$data = $pbtheme_data;