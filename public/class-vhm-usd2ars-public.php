<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://viktormorales.com
 * @since      1.0.0
 *
 * @package    Vhm_Usd2ars
 * @subpackage Vhm_Usd2ars/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Vhm_Usd2ars
 * @subpackage Vhm_Usd2ars/public
 * @author     Viktor H. Morales <viktorhugomorales@gmail.com>
 */
class Vhm_Usd2ars_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->option_name = 'vhm_usd2ars';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vhm-usd2ars-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vhm-usd2ars-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Multiply the price with the exchange rate selected from settings
	 * 
	 */
	function wc_product_get_price($price) {
		global $post, $woocommerce;
		$multiply_by = 1;

		$rate_exchange = json_decode(get_option($this->option_name . '_rate_exchange', true), true);
		$rate_exchange_default = get_option($this->option_name . '_default', true);
		
		if (isset($rate_exchange_default) && $rate_exchange_default != "") {
			$rate_exchange_default = explode('_', get_option($this->option_name . '_default', true));
			$multiply_by = $rate_exchange['dollar'][$rate_exchange_default[0]][$rate_exchange_default[1]];
		}

		return $price * $multiply_by;
	}
}
