<?php
if(!class_exists("imscpbcore")){
    class imscpbcore{
        var $name;
        var $pref;
        var $menutitle;
        var $serverurl;
        var $authurl;        
        var $memurl;
        var $state;
        var $email;
        var $authkey;
        var $levelnum;
        var $pluginfile;
        var $updateurl;
        var $pluginadmin;
        var $parentmenu;
        var $minlevel;
        
        var $icmember;
        var $icserverurl;
        var $icauthlocation;
        var $icmemberurl;

        
        function imscpbcore($Info = array("pluginname" => "", "pluginprefix" => "", "pluginfile" => "", "pluginadmin" => "", "updateurl" => "", "menutitle" => "", "serverurl" => "", "authlocation" => "", "memberurl" => "", "parentmenu"=> "")){
            $wp_dir = __FILE__;
            $wp_dir = str_replace("\\", "/", $wp_dir);
            $wp_dir = explode("/", $wp_dir);
            for($index=0; $index<5; $index++)
                unset($wp_dir[count($wp_dir) - 1]);
            $wp_dir = implode("/", $wp_dir);
            include_once($wp_dir."/wp-load.php");
            $this->name = $Info["pluginname"];
            $this->pref = $Info["pluginprefix"];
            $this->menutitle = $Info["menutitle"];
            $this->serverurl = $Info["serverurl"];
            $this->authurl = $Info["authlocation"];
            $this->memurl = $Info["memberurl"];
            $this->pluginfile = $Info['pluginfile'];
            $this->updateurl = $Info['updateurl'];
            $this->pluginadmin = $Info['pluginadmin'];
            $this->parentmenu =  $Info['parentmenu'];
            add_filter( 'http_request_args', array(&$this, 'updates_exclude'), 5, 2 );
            register_activation_hook($this->pluginfile, array(&$this, 'check_activation'));
            add_action($this->pref.'check_event', array(&$this, 'check_update'));
            register_deactivation_hook($this->pluginfile, array(&$this, 'check_deactivation'));
            $this->state =  $this->pref."activation_state";
            $this->email =  $this->pref."email";
            $this->authkey =  $this->pref."authkey";
            $this->levelnum =  $this->pref."levelnum";
            $this->minlevel =  $this->pref."minlevel";
            
            $this->icmember =  $this->pref."icmember";
            $this->icserverurl =  "imsuccesscenter.com";
            $this->icauthlocation =  "/inner_circle/wp-content/plugins/license-checker/authorize_domain.php";
            $this->icmemberurl =  "http://imsuccesscenter.com/inner_circle/wp-login.php?action=lostpassword";

		if(get_option($this->state) && get_option($this->email) != "" && get_option($this->authkey) !== "" && get_option($this->levelnum) > 0 ){
			$this->update_encryptedoption($this->state ,get_option($this->state) );            
			$this->update_encryptedoption($this->email ,get_option($this->email) );            
			$this->update_encryptedoption($this->authkey ,get_option($this->authkey) );            
			$this->update_encryptedoption($this->levelnum ,get_option($this->levelnum) );            
		}
			add_action('init', array(&$this, 'init'));
            add_filter('cron_schedules', array(&$this, 'add_12hours_cron'));
            register_activation_hook(__FILE__, array(&$this, 'CheckCron'));
            add_action($this->pref.'12hours_event', array(&$this, 'VerifyLicense'));
            add_action('admin_menu', array(&$this, 'admin_menu'),7000);
        }
        function init(){            
            $this->CheckCron();
            wp_enqueue_script('jquery');
               
            if(isset($_POST['act']) && $_POST['act'] == $this->pref.'install_license'){
                if(!isset($_POST["email"]) || !isset($_POST["authkey"]) || $_POST["email"] == "" || $_POST["authkey"] == ""){
                    $msg = "Please enter email and authorization key";
                    echo 'failure:<div class="error"><p>'.$msg.'</p></div><br />';
                    exit;
                }else{
                    if($this->VerifyLicense(trim($_POST["email"]), trim($_POST["authkey"]), trim($_POST["icmember"]))) {            
                        echo "success:Thanks! The ".$this->name." has been activated. <a href='admin.php?page=".$this->pluginadmin."'>Click here to go to the admin panel...</a>\r\n";
                        exit;
                    } else {
                        echo 'failure:<div class="error"><p>'.__("Sorry, the API key was incorrect.","").'</p></div><br />';
                        exit;
                    }
                }
            }
        }
        
        function admin_menu() {
        	global $imscpbsettings;
            if($this->get_decryptedoption($this->state) != 'true'){
        	   add_menu_page($this->menutitle, $this->menutitle, 'administrator', $this->pref.'install_license', array(&$this, 'menupage'), $imscpbsettings['icon'] );
            }elseif($this->parentmenu != "" && $this->CheckLicense()){
                $this->licensepage($this->parentmenu);    
            }
        }
        
        function ActivationMessage($ShowActive = false){
            $msg = "";
            if($this->get_decryptedoption($this->state) != 'true')
                $msg="";
            else if($ShowActive)
                $msg = '<p>'.$this->name.' is Activated </p>';
            if($msg != "")
                echo '<div id="message" class="updated fade">'.$msg.'</div>';
        }
        
        function menupage(){
        	global $imscpbsettings;
        	$buttonText = "Activate Now";
            $this->ActivationMessage(true);
            if($this->get_decryptedoption($this->email) == "-1") $this->update_encryptedoption($this->email, "");
            if($this->get_decryptedoption($this->authkey) == "-1") $this->update_encryptedoption($this->authkey, "");
?>
<div style="width:100%;height:<?php echo $imscpbsettings['headerheight'] ?>;background:black url('<?php echo $imscpbsettings['headerbg'] ?>') repeat-x;margin-right:20px;margin-bottom:20px;">
<h2 style="margin:0px;padding:0px;margin-left:-19px;margin-top:-4px;margin-bottom:20px;"><img src="<?php echo $imscpbsettings['headerlogo'] ?>"></h2></div>    
<div id="error"></div>
<div class="dvlicense">
    <h1>Please Activate Your Copy of <?php echo $this->name?></h1>
    <strong>Note - </strong> If you have spare licenses available we will automatically add this domain "<?php echo $_SERVER['SERVER_NAME']?>" to your license pool<br /><br />
    <form id="actform" method="post">
        <table>
            <tr>
                <th>Email address:</th>
                <td><input type="text" name="email" value="<?php echo $this->get_decryptedoption($this->email);?>" /></td>
            </tr>
            <tr>
                <th>Authorization key:</th>
                <td>
                    <input type="text" name="authkey" value="<?php echo $this->get_decryptedoption($this->authkey);?>" />
                    <p align=right>I'm an Inner Circle Member: <input type="checkbox" name="icmember" value="1" <?php if ($this->get_decryptedoption($this->email)==1){echo "checked";}?>/></p>
                    <a class="forgot" href="<?php echo $this->memurl;?>">Forgot Your Details? Click Here</a>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                	<input type="hidden" name="act" value="<?php echo $this->pref;?>install_license" />
                    <input type="submit" id="btnsubmit" name="btnsubmit" value="<?php echo $buttonText;?>" />
                </td>
            </tr>
        </table>
    </form>
</div>
<style>
.dvlicense{
}
.dvlicense table th{
    width: 200px;
    text-align: left;
    font-weight: bold;
    vertical-align: top;
    font-size: 12px;
}
.dvlicense table td input[type="text"]{
    width: 300px;
}
.dvlicense table td .forgot{
    display: block;
    text-align: right;
    color: blue;
    text-decoration: none;
    font-weight: bold;
    font-size: 12px;
}
.dvlicense table td #btnsubmit{
    float: right;
    font-size: 12px;
}
</style>
<script>
    var j = jQuery;
    j(document).ready(function(){
        j("#actform").submit( function () { 
            j("#btnsubmit").val("Activating please wait.....");
            j("#btnsubmit").attr("disabled", 'true');
            j.ajax({
                type: "POST",
                data : j(this).serialize(),
                cache: false,
                url: "<?php echo admin_url("admin.php?page=".$this->pref."install_license");?>",
                success: function(data){
                    if(data.indexOf("success:") >= 0){
                        data = data.replace("success:", "");
                        j("#message").html('<p>'+data+'<br /></p>');
                        j("#error").html("");
                        //alert(data);
                        window.location.href = "<?php echo $this->pluginadmin;?>"; 
                    }else{
                        //alert(data);
                        j("#error").html(data.replace("failure:", ""));
                        j("#message").html('<p><strong>Notice: - </strong> <?php echo $this->name?> needs to be <span style="color: blue;">Activated</span> before it can be used<br /></p>');
                    }
                    j("#btnsubmit").val("Activate Plugin");
                    j("#btnsubmit").removeAttr("disabled");
                }
            });
            return false;
        });
    });
</script>
<?php
        }
        
        function CheckLicense($ShowMessage = false){
            if($ShowMessage)
                $this->ActivationMessage();
            if( $this->get_decryptedoption($this->state) != 'true')
                return false;
            else
                return true;
        }
        
        function GetLevelNo(){
            $LevelNo = 0;
            if($this->CheckLicense())
                $LevelNo = (int)$this->get_decryptedoption($this->levelnum, 0);
            return $LevelNo;
        }
        
        function add_12hours_cron($schedules) {
         	$schedules['12hours'] = array(
         		'interval' => 43200,
         		'display' => __( 'Once in 12 Hours' )
         	);
         	return $schedules;
         }
         
         function CheckCron(){
            $hook = $this->pref."12hours_event";
            if(!wp_get_schedule($hook)) {
                wp_schedule_event(current_time('timestamp'), "12hours", $hook);
            }       
        }
        
        function VerifyLicense($email = "-1", $authkey = "-1", $icmember="0") {
            $msg = "";
            $activated = false;
            if($this->get_decryptedoption($this->email, "") != "" && $email == "-1")
                $email = $this->get_decryptedoption($this->email);
            
            if($this->get_decryptedoption($this->authkey, "") != "" && $authkey == "-1")
                $authkey = $this->get_decryptedoption($this->authkey);
                
            if($this->get_decryptedoption($this->icmember, "") != "" && $icmember == "0")
                $icmember = $this->get_decryptedoption($this->icmember);    
                
            $remote_access_fail = false;
        	$domain = $_SERVER['SERVER_NAME'];
            $request = '';
            
            // Check Inner Circle activation or normal activation
            if ($icmember==1){
	           $this->authurl=$this->icauthlocation;
	           $this->serverurl= $this->icserverurl;
            }
            
           	$http_request  = "GET ".$this->authurl."?email=".$email."&domain=".$domain."&authkey=".$authkey." HTTP/1.0\r\n";
        	$http_request .= "Host: ".$this->serverurl."\r\n";
            $http_request .= "Content-Type: application/x-www-form-urlencoded; charset=".get_option('blog_charset')."\r\n";
        	$http_request .= "Content-Length: ".strlen( $request )."\r\n";
        	$http_request .= "User-Agent: ".$this->name."\r\n";
        	$http_request .= "\r\n";
        	$http_request .= $request;
        	$response = '';
        	// checking for cloudfare access
        	
        	$url = 'https://www.cloudflare.com/api_json.html?a=wl&tkn=65d64290e62eeca4df7e5bb73ddde9e92614c&email=admin@imsuccesscenter.com&key='.$_SERVER['SERVER_ADDR'];
		 	$s = curl_init();
			curl_setopt($s,CURLOPT_URL,$url ); 
			curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($s, CURLOPT_RETURNTRANSFER, 1); 
		    $status = curl_getinfo($s,CURLINFO_HTTP_CODE);  
			$curlResponse = curl_exec($s);
			$curlResponse = json_decode($curlResponse);
			
   			
            if( false != ($fs = @fsockopen($this->serverurl,80,$errno,$errstr,30 ) ) ) {       
                fwrite( $fs,$http_request );
                while ( !feof( $fs ) ){
					$response .= fgets( $fs,1160 ); // One TCP-IP packet
					$pos = strpos($response, '403 Forbidden');
                }
                fclose( $fs );
                
                $response = explode( "\r\n\r\n",$response,2 );
                $returned_value = (int)trim( $response[1] );
                //$returned_value = 2;
                if( $returned_value > 0 && $returned_value >= $this->minlevel ) {
                    {//if( $this->get_decryptedoption($this->state) != 'true' ) {
                        $this->update_encryptedoption($this->state,'true');
                        $this->update_encryptedoption($this->email, $email);
                        $this->update_encryptedoption($this->authkey, $authkey);
                        $this->update_encryptedoption($this->levelnum, $returned_value);
                        $this->update_encryptedoption($this->icmember, $icmember);
                    }
                    $activated = true;
                } else {
                    if($email == "-1") $email = "";
                    if($authkey == "-1") $authkey = "";
                    $this->update_encryptedoption($this->state,"false");
                    $this->update_encryptedoption($this->email, $email);
                    $this->update_encryptedoption($this->authkey, $authkey);
                    $this->update_encryptedoption($this->levelnum, "");
                    $this->update_encryptedoption($this->icmember, $icmember);
                    $activated = false;
                }
            }
            return $activated;    
        }
        
        function updates_exclude( $r, $url ) {
        	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
        		return $r; // Not a plugin update request. Bail immediately.
        	$plugins = unserialize( $r['body']['plugins'] );
			
        	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
        	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
        	$r['body']['plugins'] = serialize( $plugins );
        	return $r;
        }
        
        function plugin_get($i) {
        	if ( ! function_exists( 'get_plugins' ) )
        		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( $this->pluginfile ) ) );
        	$plugin_file = basename( ( $this->pluginfile ) );
        	return $plugin_folder[$plugin_file][$i];
        }
        
        function check_activation() {
        	wp_schedule_event(time(), 'twicedaily', array(&$this, $this->pref.'check_event'));
        }
        
        function check_update() {
        	global $wp_version;
        	$plugin_folder = plugin_basename( dirname( $this->pluginfile ) );
        	$plugin_file = basename( ( $this->pluginfile ) );
        	if ( defined( 'WP_INSTALLING' ) ) return false;
        	$response = wp_remote_get( $this->updateurl );
        	list($version, $url) = explode('|', $response['body']);
        	if($this->plugin_get("Version") == $version) return false;
        	$plugin_transient = get_site_transient('update_plugins');
        	$a = array(
        		'slug' => $plugin_folder,
        		'new_version' => $version,
        		'url' => $this->plugin_get("AuthorURI"),
        		'package' => $url
        	);
        	$o = (object) $a;
        	$plugin_transient->response[$plugin_folder.'/'.$plugin_file] = $o;
        	set_site_transient('update_plugins', $plugin_transient);
        }
        
        function check_deactivation() {
        	wp_clear_scheduled_hook($this->pref.'check_event');
        }
        
        function licensepage($parent_slug, $menu_title = "Update License"){
            add_submenu_page($parent_slug, $menu_title, $menu_title, 'manage_options', $parent_slug.'-updatelicense', array (&$this, 'updatelicense') );
        }
        
        function updatelicense($buttonText = "Update License"){
            $this->menupage($buttonText);
        }
        
        
        function encryptoptions($array) {
            $compressedarray = json_encode($array);
            $encrypted       = urlencode(strtr(base64_encode(addslashes(serialize($compressedarray))), '+/=', '-_,'));
            update_option($this->pref . "data", $encrypted);
        }
        
        function update_encryptedoption($item, $value) {
            $encrypted                = get_option($this->pref . "data");
            $decryptedoptions         = urldecode(unserialize(stripslashes(base64_decode(strtr($encrypted, '-_,', '+/=')))));
            $uncompressedarray        = json_decode($decryptedoptions, true);
            $uncompressedarray[$item] = $value;
            $compressedarray          = json_encode($uncompressedarray);
            $encrypted                = urlencode(strtr(base64_encode(addslashes(serialize($compressedarray))), '+/=', '-_,'));
            update_option($this->pref . "data", $encrypted);
        }
        
        function get_decryptedoption($item) {
            $encryptedoptions  = get_option($this->pref . "data");
            $decryptedoptions  = urldecode(unserialize(stripslashes(base64_decode(strtr($encryptedoptions, '-_,', '+/=')))));
            $uncompressedarray = json_decode($decryptedoptions, true);
            return $uncompressedarray[$item];
        }

        
        
    };
}
?>