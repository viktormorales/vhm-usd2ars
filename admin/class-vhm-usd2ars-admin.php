<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://viktormorales.com
 * @since      1.0.0
 *
 * @package    Vhm_Usd2ars
 * @subpackage Vhm_Usd2ars/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vhm_Usd2ars
 * @subpackage Vhm_Usd2ars/admin
 * @author     Viktor H. Morales <viktorhugomorales@gmail.com>
 */
class Vhm_Usd2ars_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The option name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $option_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->option_name = 'vhm_usd2ars';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vhm_Usd2ars_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vhm_Usd2ars_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vhm-usd2ars-admin.css', array(), time(), 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vhm_Usd2ars_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vhm_Usd2ars_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vhm-usd2ars-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'VHM USD2ARS Settings', $this->plugin_name ),
			__( 'VHM USD2ARS', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}
	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/'.$this->plugin_name.'-admin-display.php';
	}
	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_settings() {
		register_setting( $this->plugin_name, $this->option_name . '_rate_exchange' );
		register_setting( $this->plugin_name, $this->option_name . '_default' );
		register_setting( $this->plugin_name, $this->option_name . '_decimals' );
	}

	/**
	 * Show widget in the admin dashboard 
	 *
	 * @since  1.0.0
	 */
	public function add_dashboard_widgets() {
		global $wp_meta_boxes;
		wp_add_dashboard_widget('vhm_usd2ars', __('VHM USD2ARS', $this->plugin_name), array($this, 'dashboard_widget') );
		
	}
	public function dashboard_widget() {
		?>
			<div id="vhm-usd2ars-dashboard-widget">
				<p><strong><?php _e('Last updated', $this->plugin_name)?>:</strong>
				<?php echo date('d/m/Y H:i:s', get_option($this->option_name . '_last_updated')); ?></p>

				<?php 
					$rate_exchange = json_decode(get_option($this->option_name . '_rate_exchange', 
					true), true);

					foreach ($rate_exchange['dollar'] as $key => $dollar) {
						?>
						<h2><?php echo $key; ?></h2>
						<div class="vhm-usd2ars-info-box">
							<div class="vhm-usd2ars-content-info-box">
								<h3><?php _e('Buy', $this->plugin_name)?></h3>
								<span>ars<?php echo $dollar['buy']; ?></span>
							</div>
							<div class="vhm-usd2ars-content-info-box">
								<h3><?php _e('Sell', $this->plugin_name)?></h3>
								<span>ars<?php echo $dollar['sell']; ?></span>
							</div>
						</div>
						<?php
					}
				?>

				<p><a href="<?php echo admin_url('options-general.php?page=vhm-usd2ars'); ?>" class="button button-primary"><?php _e('Go to settings', $this->plugin_name); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Register the REST API ROUTES
	 * 
	 */
	public function register_rest_routes(){
		$namespace = $this->plugin_name . '/v1';
		register_rest_route( $namespace, '/update', array(
			'methods' => 'POST',
			'callback' => array($this,'update_exchange_rate'),
			'permission_callback' => function($request) {
                return current_user_can('manage_options');
            },
		) );
	}

	/**
	 * Callback functions for API calls
	 * 
	 */
	public function update_exchange_rate(WP_REST_Request $req) {
		$json = $req->get_body();

		update_option($this->option_name . '_last_updated', current_time('timestamp'));
		update_option($this->option_name . '_rate_exchange', $json);
		
		return new WP_REST_Response(
			[
				'status' => 202,
				'response' => 'Dollar updated',
				'body_response' => $json
			]
		);
	}
}
