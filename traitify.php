<?php
/**
 * Plugin Name: Traitify
 * Plugin URI: http://www.smackcoders.com
 * Description: Plugin ask some question to users to know about their personality
 * Version: 1.0.0
 * Author: Smackcoders
 * Author URI: http://www.smackcoders.com
 * License: GPL2
 */
// Exit if accessed directly
if(!defined('ABSPATH'))
        exit;

define('WP_TRAITIFY_NAME', 'Traitify');
define('WP_TRAITIFY_VERSION', '1.0');
define('WP_TRAITIFY_SLUG', 'traitify');
define('WP_TRAITIFY_URL', WP_PLUGIN_URL . '/' . WP_TRAITIFY_SLUG . '/');
define('WP_TRAITIFY_DIR', plugin_dir_path(__FILE__) . '/');


global $traitify;
class wp_traitify
{
	private static $instance;

	public $host = 'api.traitify.com';

	public $table_name = 'traitify';

	public $version = 'v1';

#	public $secretKey = '3t7rm5qms3t5hovvhuf52dcng8';

#	public $publicKey = '8nin4kjf9era1ge1mklhh4m2qi';

	public $secretKey = '';

	public $publicKey = '';

	public $types = array('career-deck' => 'Career Deck', 'core' => 'Core', 'super-hero' => 'Super Hero', 'introvert-extrovert' => 'Introvert Extrovert', 'movies' => 'Movies', 'persuasion' => 'Persuasion');

	public $shortcode = 'wp-traitify-shortcode';

	public $option_name = 'wp_traitify_assessment_';

	/**
	 * create new instance of traitify
	 * @return object
	 */
	public static function instance()	{
		if(!isset(self::$instance))	{
			self::$instance = new wp_traitify;

			/**
			 * Verification for vendor directory whether is available or not
			 **/
			if(file_exists(WP_TRAITIFY_DIR . 'libs/vendor/autoload.php')) {
				require_once(WP_TRAITIFY_DIR . 'libs/client.php');
				$get_traitity_settings = get_option('traitify_settings');
				if(!empty($get_traitity_settings)) {
					self::$instance->secretKey = $get_traitity_settings['secretkey'];
					self::$instance->publicKey = $get_traitity_settings['publickey'];
				}
				$api_details = array('host' => self::$instance->host, 'version' => self::$instance->version, 'secretKey' => self::$instance->secretKey);
				self::$instance->client = new Traitify\Client($api_details);
			}
			add_action('init', array('wp_traitify', 'wp_traitify_frontend_init'));
			add_action('admin_menu', array('wp_traitify', 'wp_traitify_menu'));
			add_action('admin_init', array('wp_traitify', 'wp_traitify_admin_init'));
		}
		return self::$instance;
	}

	public static function registration()	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'traitify';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE `{$table_name}` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `user_id` int(10) NOT NULL,
		  `assessment_id` varchar(255) NOT NULL,
		  `type` varchar(50) NOT NULL,
		  `created_at` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`)
		)";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	/**	
	 * add js and css for frontend 
	 */
	public static function wp_traitify_frontend_init()	{
		wp_register_script('wp-traitify-js', plugins_url('public/js/traitify.js', __FILE__));
                wp_enqueue_script('wp-traitify-js');
		wp_enqueue_style('traitify-bootstrap-css', plugins_url('public/css/traitify.css', __FILE__));
	}

	/**
 	 * add js and css for admin end
	 */ 
	public static function wp_traitify_admin_init()	{
		if(isset($_REQUEST['page']) && ($_REQUEST['page'] == WP_TRAITIFY_SLUG || $_REQUEST['page'] == WP_TRAITIFY_SLUG . '-list'))	                {
			wp_enqueue_style('traitify-bootstrap-css', plugins_url('public/css/traitify.css', __FILE__));
                        wp_enqueue_style('traitify-font-awesome-css', plugins_url('public/css/font-awesome.css', __FILE__));

			wp_register_script('wp-traitify-modal-js', plugins_url('public/js/bootstrap-modals.js', __FILE__));
                	wp_enqueue_script('wp-traitify-modal-js');
		}
	}

	public static function wp_traitify_menu() {
		add_menu_page(WP_TRAITIFY_NAME, WP_TRAITIFY_NAME, 'manage_options', WP_TRAITIFY_SLUG, 'traitify_view');
		add_submenu_page(WP_TRAITIFY_SLUG, 'Users Lists', 'Users List', 'manage_options', WP_TRAITIFY_SLUG . '-list', 'traitify_list');
		add_submenu_page(WP_TRAITIFY_SLUG, 'Traitify Settings', 'Settings', 'manage_options', WP_TRAITIFY_SLUG . '-settings', 'traitify_settings');

	}

	/**	
	 * return assessment id
	 * @param string $type
	 * @param integer $user_id
	 * @return mixed $assessmentId
	 */
	public function getAssessmentId($type, $user_id)	{
		global $wpdb;
		$assessmentId = false;
		$getAssessmentId = $wpdb->get_results("select assessment_id from {$wpdb->prefix}{$this->table_name} where user_id = '{$user_id}' and type = '{$type}'");
		if(empty($getAssessmentId))	{
			$current_time = date('Y-m-d H:i:s');
			$assessmentInfo = $this->client->createAssessment($type);
			$assessmentId = $assessmentInfo->id;
			$wpdb->query("insert into {$wpdb->prefix}{$this->table_name} (user_id, assessment_id, type, created_at) values ('$user_id', '$assessmentId', '$type', '$current_time')");
		}
		else	{
			$assessmentId = $getAssessmentId[0]->assessment_id;
		}
		return $assessmentId;
	}

	/**
 	 * return html element which will show the questions
	 * @param string $type
	 * @return html $html
 	 */
	public function show_questions($type = false)	{
		$user_id = get_current_user_id();
		if(empty($user_id))	{
			return "Please login to start the test";
		}
		if(empty($type))
			$type = 'career-deck';

		$assessmentId = $this->getAssessmentId($type, $user_id);
		$html = '<div class="traitify_assessment"></div>
	    		<script>
      				Traitify.setPublicKey("' . $this->publicKey . '");
      				Traitify.setHost("' . $this->host . '");
      				Traitify.setVersion("' . $this->version . '");
      				Traitify.ui.load("' . $assessmentId . '", ".traitify_assessment")
    			</script>';
		return $html;
	}
}

/** 
 * show traitify form to user (both via shortcode and code)
 * @param mixed $attr
 */
function traitify_show_questions($attr)	{
	global $traitify;
	// if $attr is array, then parameter passed from shortcode else from normal code
	if(is_array($attr))
		$type = $attr['type'];
	else
		$type = $attr;

	echo $traitify->show_questions($type);
}

/**
 * list view to show the users
 * who attended traitify
 */
function traitify_list()	{
	/**
	 * Verification for vendor directory whether is available or not
	 **/
	if(!file_exists(WP_TRAITIFY_DIR . 'libs/vendor/autoload.php')) {
		activating_traitify();
		die;
	} else {
		global $traitify;
		if(isset($_REQUEST['assessment_id']) && !empty($_REQUEST['assessment_id']))	{
			traitify_result();
		}
		else	{
			require_once(WP_TRAITIFY_DIR . 'templates/list.php');
			$list = new Traitify_List_Table();
			echo "<div style='width:98%;'>";
			$list->prepare_items(); 
			$list->display(); 
			echo "</div>";
		}
	}
}

/**
 * Traitify Settings
 */
function traitify_settings() {
	/**
	 * Verification for vendor directory whether is available or not
	 **/
	if(!file_exists(WP_TRAITIFY_DIR . 'libs/vendor/autoload.php')) {
		activating_traitify();
		die;
	} else {
		global $traitify;
		require_once(WP_TRAITIFY_DIR . 'templates/settings.php');
		$settings = new Traitify_Settings();
		$settings->save_settings();
	}
}

function activating_traitify() {
	echo "<div style='width:98%; margin-top: 25%;' align='center'> <div class='alert'>
		<p><strong>Warning!</strong> Vendor library is missing. Please download the library files from <a href='http://code.smackcoders.com/traitify/downloads/vendor.zip' target='_blank' style='text-decoration:none;'>Here!</a>.</p> <p>Unzip the library files into your libs directory in plugin root. (<b>Path:-</b> {plugins_directory}/traitify/libs)</p>
		</div></div>";
}

/**
 * test result will be shown here
 * @param mixed $assessmentId
 */
function traitify_result($assessmentId = false)	{
	global $traitify;
	if(empty($assessmentId))
		$assessmentId = $_REQUEST['assessment_id'];

	if(empty($assessmentId))	{
		echo 'Please pass assessment id';
	}
	else	{
		$result = $traitify->client->get('/assessments/' . $assessmentId . '?data=blend,types,traits,career_matches');
		if(isset($result->completed_at))	{
			require_once(WP_TRAITIFY_DIR . 'templates/show_result.php');
		}
		else	{
			echo 'Test not completed yet. Please take the test again';
		}
	}
}

function traitify_view()	{
	/**
	 * Verification for vendor directory whether is available or not
	 **/
	if(!file_exists(WP_TRAITIFY_DIR . 'libs/vendor/autoload.php')) {
		activating_traitify();
		die;
	} else {
		global $traitify;
		require_once(WP_TRAITIFY_DIR . 'templates/index.php');
	}
}

function traitify_init()	{
	return wp_traitify::instance();
}

$traitify = traitify_init();
add_shortcode('wp-traitify-shortcode', 'traitify_show_questions');
register_activation_hook(__FILE__, array('wp_traitify', 'registration'));

