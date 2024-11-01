<?php
/*
Plugin Name: Youtube with fancy zoom
Plugin URI: http://www.gopiplus.com/work/2010/07/18/youtube-with-fancy-zoom/
Description: Youtube with fancy zoom plugin is a media viewing application that supports webs most popular youtube video. This is a jQuery based fancy zoom.  
Author: Gopi Ramasamy
Version: 12.0
Author URI: http://www.gopiplus.com/work/2010/07/18/youtube-with-fancy-zoom/
Donate link: http://www.gopiplus.com/work/2010/07/18/youtube-with-fancy-zoom/
Text Domain: youtube-with-fancy-zoom
Domain Path: /languages
*/

global $wpdb, $wp_version;
define("WP_G_YWFZ_TABLE", $wpdb->prefix . "g_ywfz");
define('WP_G_YWFZ_FAV', 'http://www.gopiplus.com/work/2010/07/18/youtube-with-fancy-zoom/');

if ( ! defined( 'WP_G_YWFZ_BASENAME' ) )
	define( 'WP_G_YWFZ_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_G_YWFZ_PLUGIN_NAME' ) )
	define( 'WP_G_YWFZ_PLUGIN_NAME', trim( dirname( WP_G_YWFZ_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_G_YWFZ_PLUGIN_URL' ) )
	define( 'WP_G_YWFZ_PLUGIN_URL', plugins_url() . '/' . WP_G_YWFZ_PLUGIN_NAME );
	
if ( ! defined( 'WP_G_YWFZ_ADMIN_URL' ) )
	define( 'WP_G_YWFZ_ADMIN_URL', admin_url() . 'options-general.php?page=youtube-with-fancy-zoom' );
	
function g_ywfz_show($arr) 
{
	$ArrInput 			= array();
	$ArrInput["videoid"] 	= $arr["g_ywfz_id"];
	echo g_ywfz_shortcode( $ArrInput );
}

function ywfz_show( $videoid = 0 )
{
	global $wpdb;
	$ArrInput = array();
	$ArrInput["videoid"] = $videoid;
	echo g_ywfz_shortcode( $ArrInput );
}

function g_ywfz_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_G_YWFZ_TABLE . "'") != WP_G_YWFZ_TABLE) 
	{
		$wpdb->query("
			CREATE TABLE `". WP_G_YWFZ_TABLE . "` (
				`ywfz_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`ywfz_title` VARCHAR( 1024 ) NOT NULL ,
				`ywfz_watch` VARCHAR( 1024 ) NOT NULL ,
				`ywfz_code` VARCHAR( 100 ) NOT NULL ,
				`ywfz_img` VARCHAR( 200 ) NOT NULL ,
				`ywfz_size` VARCHAR( 10 ) NOT NULL ,
				`ywfz_imglink` VARCHAR( 1024 ) NOT NULL ,
				`ywfz_status` VARCHAR( 10 ) NOT NULL ,
				`ywfz_sidebar` VARCHAR( 10 ) NOT NULL ,
				`ywfz_expire` DATE NOT NULL
				)  ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		$sSql = "insert into `". WP_G_YWFZ_TABLE . "` set `ywfz_title`='Billionaire Bill Gates Guesses Grocery Store Prices', ";
		$sSql = $sSql . "`ywfz_watch`='http://www.youtube.com/watch?v=ad_higXixRA', ";
		$sSql = $sSql . "`ywfz_code`='ad_higXixRA', ";
		$sSql = $sSql . "`ywfz_img`='Youtube Thumbnail', ";
		$sSql = $sSql . "`ywfz_size`='0', `ywfz_imglink`='', ";
		$sSql = $sSql . "`ywfz_status`='Yes', `ywfz_sidebar`='Yes';";
		$wpdb->query($sSql);
		$sSql = "insert into `". WP_G_YWFZ_TABLE . "` set";
		$sSql = $sSql . " `ywfz_title`='Email newsletter wordpress plugin',";
		$sSql = $sSql . "`ywfz_watch`='http://www.youtube.com/watch?v=m0kWQOI8HOg',";
		$sSql = $sSql . "`ywfz_code`='m0kWQOI8HOg',";
		$sSql = $sSql . "`ywfz_img`='Youtube Thumbnail', ";
		$sSql = $sSql . "`ywfz_size`='0', `ywfz_imglink`='', ";
		$sSql = $sSql . "`ywfz_status`='Yes', `ywfz_sidebar`='Yes';";
		$wpdb->query($sSql);
	}
}

function g_ywfz_admin_option() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-edit.php');
			break;
		case 'add':
			include('pages/content-add.php');
			break;
		case 'set':
			include('pages/content-setting.php');
			break;
		default:
			include('pages/content-show.php');
			break;
	}
}

class g_ywfz_widget_register extends WP_Widget 
{
	function __construct() 
	{
		$widget_ops = array('classname' => 'widget_text youtube-fancy-zoom-widget', 'description' => __('Youtube fancy zoom', 'youtube-with-fancy-zoom'), 'youtube-fancy-zoom');
		parent::__construct('youtube-fancy-zoom', __('Youtube fancy zoom', 'youtube-with-fancy-zoom'), $widget_ops);
	}
	
	function widget( $args, $instance ) 
	{
		extract( $args, EXTR_SKIP );

		$title 				= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$g_ywfz_id			= $instance['g_ywfz_id'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
		{
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// Call widget method
		$arr = array();
		$arr["g_ywfz_id"] 	  = $g_ywfz_id;
		g_ywfz_show($arr);
		
		// Call widget method
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) 
	{
		$instance 					= $old_instance;
		$instance['title'] 			= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['g_ywfz_id'] 		= ( ! empty( $new_instance['g_ywfz_id'] ) ) ? strip_tags( $new_instance['g_ywfz_id'] ) : '';
		return $instance;
	}
	
	function form( $instance ) 
	{
		$defaults = array(
			'title' 			=> '',
			'g_ywfz_id' 		=> ''
        );
		
		$instance 		= wp_parse_args( (array) $instance, $defaults);
        $title 			= $instance['title'];
		$g_ywfz_id 		= $instance['g_ywfz_id'];
	
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'youtube-with-fancy-zoom'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('g_ywfz_id'); ?>"><?php _e('Video ID (Enter 0 to display random one)', 'youtube-with-fancy-zoom'); ?> </label>
            <input class="widefat" id="<?php echo $this->get_field_id('g_ywfz_id'); ?>" name="<?php echo $this->get_field_name('g_ywfz_id'); ?>" type="text" value="<?php echo $g_ywfz_id; ?>" />
        </p>
		<?php
	}
	
	function g_ywfz_render_selected($var) 
	{
		if ($var==1 || $var==true) 
		{
			echo 'selected="selected"';
		}
	}
}

function g_ywfz_widget_loading()
{
	register_widget( 'g_ywfz_widget_register' );
}

function g_ywfz_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page( __('Youtube fancy zoom', 'youtube-with-fancy-zoom'), 
				__('Youtube fancy zoom', 'youtube-with-fancy-zoom'), 'manage_options', 'youtube-with-fancy-zoom', 'g_ywfz_admin_option' );
	}
}

function g_ywfz_shortcode( $atts ) 
{
	global $wpdb;
	global $ScriptInserted;
	global $JsScriptInserted;
	$videoid = 0;
	$g_ywfz_pp = "";
	
	//[youtube-fancy-zoom videoid="1"]	 // Thuis is plugin short code.
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$videoid = $atts['videoid'];
	if(!is_numeric($videoid)){ $videoid = 0; } 
	
	$sSql = "select * from ". WP_G_YWFZ_TABLE . " where 1=1";
	if($videoid > 0)
	{
		 $sSql = $sSql. " and ywfz_id=".$videoid;
	}
	else
	{
		$sSql = $sSql. " and ywfz_status='YES'";
	}
	$sSql = $sSql. " ORDER BY rand() limit 0,1";
	
	$data = $wpdb->get_results($sSql);
	
	if ( ! empty($data) ) 
	{
		foreach ( $data as $data ) 
		{ 
			if($data->ywfz_img == 'Youtube Thumbnail')
			{
				$imgsource = '<img src="http://img.youtube.com/vi/'.$data->ywfz_code.'/'.$data->ywfz_size.'.jpg" alt="'.$data->ywfz_title.'" />';
			}
			else
			{
				$imgsource = '<img src="'.$data->ywfz_imglink.'" alt="'.$data->ywfz_title.'" />';
			}	

			$g_ywfz_pp = $g_ywfz_pp . '<a data-fancybox href="'.$data->ywfz_watch.'">'; 
			  $g_ywfz_pp = $g_ywfz_pp . $imgsource;
			$g_ywfz_pp = $g_ywfz_pp . '</a>'; 
		}
	}
	else
	{
		$g_ywfz_pp = __('No data found.', 'youtube-with-fancy-zoom');
	}
	return $g_ywfz_pp;
}

function g_ywfz_textdomain() 
{
	  load_plugin_textdomain( 'youtube-with-fancy-zoom', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function g_ywfz_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'youtube-with-fancy-zoom':
				wp_register_script( 'g_ywfz-adminscripts', plugins_url( 'pages/setting.js', __FILE__ ), '', '', true );
				wp_enqueue_script( 'g_ywfz-adminscripts' );
				$g_ywfz_select_params = array(
					'ywfz_title'   	=> __( 'Please enter the title.', 'g_ywfz-select', 'youtube-with-fancy-zoom' ),
					'ywfz_watch'   	=> __( 'Please enter the video link.', 'g_ywfz-select', 'youtube-with-fancy-zoom' ),
					'ywfz_code' 	=> __( 'Please enter the video code.', 'g_ywfz-select', 'youtube-with-fancy-zoom' ),
					'ywfz_img' 		=> __( 'Please select the display image.', 'g_ywfz-select', 'youtube-with-fancy-zoom' ),
					'ywfz_status'  	=> __( 'Please select the display status.', 'g_ywfz-select', 'youtube-with-fancy-zoom' ),
					'ywfz_sidebar' 	=> __( 'Please select the sidebar display.', 'g_ywfz-select', 'youtube-with-fancy-zoom' ),
					'ywfz_delete'	=> __( 'Do you want to delete this record?', 'g_ywfz-select', 'youtube-with-fancy-zoom' ),
				);
				wp_localize_script( 'g_ywfz-adminscripts', 'g_ywfz_adminscripts', $g_ywfz_select_params );
				break;
		}
	}
}

function g_ywfz_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'youtube-with-fancy-zoom-css', WP_G_YWFZ_PLUGIN_URL.'/includes/youtube-with-fancy-zoom.css');
		wp_enqueue_script( 'youtube-with-fancy-zoom-js', WP_G_YWFZ_PLUGIN_URL.'/includes/youtube-with-fancy-zoom.js');
	}	
}

add_action('wp_enqueue_scripts', 'g_ywfz_javascript_files');
add_shortcode( 'youtube-fancy-zoom', 'g_ywfz_shortcode' );
add_action('plugins_loaded', 'g_ywfz_textdomain');
add_action('admin_menu', 'g_ywfz_add_to_menu');
register_activation_hook(__FILE__, 'g_ywfz_install');
register_deactivation_hook(__FILE__, 'g_ywfz_deactivation');
add_action( 'widgets_init', 'g_ywfz_widget_loading');
add_action( 'admin_enqueue_scripts', 'g_ywfz_adminscripts' );
?>