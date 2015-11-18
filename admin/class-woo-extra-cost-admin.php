<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Extra_Cost
 * @subpackage Woo_Extra_Cost/admin
 * @author     Multidots <wordpress@multidots.com>
 */
class Woo_Extra_Cost_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-extra-cost-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-extra-cost-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Register hooks for add tabs in Product
	 *
	 * @since    1.0.0
	 */
	public function woo_extra_cost_product_tab(){
		add_action('woocommerce_product_write_panel_tabs', array($this, 'woo_extra_cost_product_write_panel_tab'));
        add_action('woocommerce_product_write_panels', array($this, 'woo_extra_cost_product_write_panel'));
        add_action('woocommerce_process_product_meta', array($this, 'woo_extra_cost_product_save_data'), 10, 2);
	}
	
	/**
	 * Register Product tab
	 *
	 * @since    1.0.0
	 */
	public function woo_extra_cost_product_write_panel_tab() {
        echo "<li class=\"activation_tab\"><a href=\"#activation_tab\">"  . __('Product Extra Cost', 'woocommerce-extra-features') . "</a></li>";
    }

    
    /**
	 * Register Product write panel
	 *
	 * @since    1.0.0
	 */
    public function woo_extra_cost_product_write_panel() {

        global $post;

        echo '<br><br><div id="activation_tab" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';
        woocommerce_wp_checkbox(array('id' => '_extra_cost_enable', 'label' => __('Enable/Disable', 'woocommerce-extra-cost'), 'description' => __('Enable Extra Cost', 'woocommerce-extra-cost')));
        woocommerce_wp_text_input(array('id' => '_extra_cost_label', 'label' => __('Extra Cost Label', 'woocommerce-extra-cost'), 'description' => __('Extra Cost label ', 'woocommerce-extra-cost'), 'class' => 'short'));
        woocommerce_wp_text_input(array('id' => '_extra_cost_amount', 'label' => __('Extra Cost amount', 'woocommerce-extra-cost'), 'description' => __('Extra Cost per Product', 'woocommerce-extra-cost'), 'class' => 'short wc_input_price'));

        echo '</div>';
    }

    
    /**
	 * Register Product tab data save
	 *
	 * @since    1.0.0
	 */
    public function woo_extra_cost_product_save_data($post_id, $post) {
		
        if (isset($_POST['_extra_cost_enable'])) {
            update_post_meta($post_id, '_extra_cost_enable', $_POST['_extra_cost_enable']);
        } else {
        	 update_post_meta($post_id, '_extra_cost_enable', '');
        }

        if (isset($_POST['_extra_cost_label'])) {
            update_post_meta($post_id, '_extra_cost_label', $_POST['_extra_cost_label']);
        }
	
		 if (isset($_POST['_extra_cost_amount'])) {
            update_post_meta($post_id, '_extra_cost_amount', $_POST['_extra_cost_amount']);
        }
    }
    
    public function woo_extra_cost_admin_init_own(){
		require_once 'partials/woo-extra-cost-admin-display.php';
		$admin = new WC_Settings_Extra_Cost();
    }

}
