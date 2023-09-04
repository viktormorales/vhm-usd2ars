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

		$rates = json_decode(get_option($this->option_name . '_rates', true), true);
		$selected_rate = get_option($this->option_name . '_selected_rate', true);

		$display_price = get_option($this->option_name . '_display_price');
		
		if ($display_price !== "ars") {
			if (isset($selected_rate) || $selected_rate !== '') {
				$selected_rate = explode('_', get_option($this->option_name . '_selected_rate', true));
				$multiply_by = $rates['dollar'][$selected_rate[0]][$selected_rate[1]];
			}
		}

		return $price * $multiply_by;
	}

	/**
	 * Add a USD rate conversion reference after the price shown in argentinian pesos
	 */
	function wc_usd_reference_after_price($price, $pid) {
		$display_price = get_option($this->option_name . '_display_price');

		if ($display_price == "ars") {

			$rates = json_decode(get_option($this->option_name . '_rates', true), true);
			$selected_rate = get_option($this->option_name . '_selected_rate', true);
			
			$selected_rate = explode('_', get_option($this->option_name . '_selected_rate', true));
			$ars2usd = round($pid->get_price() / number_format((float)$rates['dollar'][$selected_rate[0]][$selected_rate[1]],2,",",""), 2);

			$usd_reference_text = get_option($this->option_name . '_usd_reference_text');

			$usd_reference = '<span class="woocommerce-Usd-reference usd-reference">';
			$usd_reference .= $ars2usd . 'us$';
			$usd_reference .= ($usd_reference_text) ? ' ' . $usd_reference_text : '';
			$usd_reference .= '</span>';
		}

		return $price . $usd_reference;
	}

}
