<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Extra_Cost
 * @subpackage Woo_Extra_Cost/public
 * @author     Multidots <wordpress@multidots.com>
 */
class Woo_Extra_Cost_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-extra-cost-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-extra-cost-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * WooCommerce Extra Feature
	 * --------------------------
	 *
	 * Add custom fee to cart automatically
	 *
	 */
	function woo_add_cart_fee() {

		global $woocommerce,$post;

		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

		foreach ($woocommerce->cart->cart_contents as $key => $values ) {
			$is_enable_extra_cost = get_post_meta($values['product_id'], '_extra_cost_enable',true);

			if ( $is_enable_extra_cost != '' && !empty( $is_enable_extra_cost ) ) {
				$extra_cost = get_post_meta($values['product_id'], '_extra_cost_amount',true);
				$extra_cost_lable = get_post_meta($values['product_id'], '_extra_cost_label',true);
				$woocommerce->cart->add_fee( apply_filters( 'extra_feature_extra_cost_name', $extra_cost_lable.': '.get_the_title($values['product_id'] ) ), $extra_cost, true, 'standard' );

			}
		}
	}


	/**
	 * WooCommerce Extra Feature
	 * --------------------------
	 *
	 * Add custom fee to cart automatically
	 *
	 */
	function woo_add_cart_fee_based_on_country() {

		global $woocommerce,$post;

		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;
		global $wpdb;
		$county_array = array();
		$rates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_extra_cost");
				if ( !empty($rates) && isset($rates) ) { 
				
					foreach ( $rates as $rate ) {
						$county_array[] = $rate->extra_cost_country_code;
					}
				}
		$county 	= $county_array;
		$selected_country = $woocommerce->customer->get_shipping_country();
		$get_extra_rate = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}woocommerce_extra_cost where extra_cost_country_code ='$selected_country'");
		
		if ( in_array( $woocommerce->customer->get_shipping_country(), $county ) ) :
		$surcharge = $get_extra_rate->extra_cost_value;
		$woocommerce->cart->add_fee( 'Extra cost based on selected country:', $surcharge, true, '' );
		endif;
	}
	
	/**
	 * BN code added
	 */
	
	function paypal_bn_code_filter($paypal_args) {
		$paypal_args['bn'] = 'Multidots_SP';
		return $paypal_args;
	}


}
