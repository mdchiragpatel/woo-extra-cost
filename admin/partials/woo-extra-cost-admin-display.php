<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woo_Extra_Cost
 * @subpackage Woo_Extra_Cost/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Settings_Extra_Cost {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Class hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		// Add WC settings tab
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'settings_tab_extra_cost' ), 60 );

		// Settings page contents
		add_action( 'woocommerce_settings_tabs_extra_cost_settings', array( $this, 'settings_page_extra_cost' ) );

		// Save settings page
		add_action( 'woocommerce_update_options_extra_cost_settings', array( $this, 'update_options_extra_cost' ) );
	}

	/**
	 * Settings tab.
	 *
	 * Add a WooCommerce settings tab for the Receiptful settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array	$tabs 	Array of default tabs used in WC.
	 * @return 	array 			All WC settings tabs including newly added.
	 */
	public function settings_tab_extra_cost( $tabs ) {

		$tabs['extra_cost_settings'] = __( 'Woo Extra Cost', 'woocommerce-extra-cost' );

		return $tabs;

	}

	/**
	 * Settings page content.
	 *
	 * @since 1.0.0
	 */
	public function settings_page_extra_cost() { 
		global $wpdb;
		if ( ! defined( 'ABSPATH' ) ) {
			exit;
		}
		?>
		<h3><?php printf( __( 'Extra Cost Country Base', 'woocommerce' ) ); ?></h3>
		<table class="wc_extra_cost wc_input_table sortable widefat">
			<thead>
				<tr>
					<th width="4%" class="sort">&nbsp;</th>
					<th width="8%"><?php _e( 'Extra&nbsp;Cost&nbspCountry&nbsp;Code', 'woocommerce' ); ?>&nbsp;<span class="tips" data-tip="<?php esc_attr_e( 'Country Code.', 'woocommerce' ); ?>">[?]</span></th>
					<th width="40%"><?php _e( 'Extra&nbsp;Cost&nbsp;name', 'woocommerce' ); ?>&nbsp;<span class="tips" data-tip="<?php esc_attr_e('Extra Cost name.', 'woocommerce'); ?>">[?]</span></th>
					<th width="48%"><?php _e( 'Extra&nbsp;Cost&nbsp;value', 'woocommerce' ); ?>&nbsp;<span class="tips" data-tip="<?php esc_attr_e( 'Extra Cost value.', 'woocommerce' ); ?>">[?]</span></th>
					
				</tr>
			</thead>
			<tbody id="rates">
			<?php
				$rates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_extra_cost");
				if ( !empty($rates) && isset($rates) ) { 
				
					foreach ( $rates as $rate ) {
						?>
						<tr class="tips" data-tip="<?php echo __( 'Extra Cost ID', 'woocommerce' ) ?>">
							<td width="4%" class="sort"><input type="hidden" class="remove_cost_rate" name="remove_cost_rate[<?php echo $rate->extra_cost_rate_id; ?>]" value="0" /></td>
							
							<td class="name" width="8%">
								<input type="text" value="<?php echo $rate->extra_cost_country_code; ?>" name="extra_cost_country_code[<?php echo $rate->extra_cost_rate_id; ?>]" />
							</td>
							
							<td class="name" width="40%">
								<input type="text" value="<?php echo $rate->extra_cost_name; ?>" name="extra_cost_name[<?php echo $rate->extra_cost_rate_id; ?>]" />
							</td>
							
							<td class="rate" width="48%">
								<input type="number" step="any" min="0" value="<?php echo $rate->extra_cost_value; ?>" placeholder="0" name="extra_cost_value[<?php echo $rate->extra_cost_rate_id ?>]" />
							</td>
						</tr>
						<?php
					}
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="10">
						<a href="#" class="button plus insert"><?php _e( 'Insert row', 'woocommerce' ); ?></a>
						<a href="#" class="button minus remove_tax_rates"><?php _e( 'Remove selected row(s)', 'woocommerce' ); ?></a>
					</th>
				</tr>
			</tfoot>
		</table>
	<?php
	}

	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function update_options_extra_cost() {
		global $woocommerce,$post,$wpdb;
		
		if ( ! empty( $_POST['extra_cost_name'] ) ) {
			$this->save_extra_cost();
		}
	}
		
	/**
	 * Save Extra cost rates.
	 *
	 * @since 1.0.0
	 */
	public function save_extra_cost() {
		global $wpdb;

		// get the tax rate id of the first submited row
		$first_extra_cost_id = key( $_POST['extra_cost_name'] );

		// Loop posted fields
		foreach ( $_POST['extra_cost_name'] as $key => $value ) {
			$mode        = 0 === strpos( $key, 'new-' ) ? 'insert' : 'update';
			$extra_cost_rate    = $this->get_posted_extra_cost( $key );

			if ( 'insert' === $mode ) {
				$extra_cost_rate_id = WC_Settings_Extra_Cost::_insert_extra_cost( $extra_cost_rate );
			} elseif ( 1 == $_POST['remove_cost_rate'][ $key ] ) {
				$extra_cost_rate_id = absint( $key );
				WC_Settings_Extra_Cost::_delete_extra_cost( $extra_cost_rate_id );
				continue;
			} else {
				$extra_cost_rate_id = absint( $key );
				WC_Settings_Extra_Cost::_update_extra_cost( $extra_cost_rate_id, $extra_cost_rate );
			}
		}
	}
	
	/**
	 * get Extra cost array
	 *
	 * @since 1.0.0
	 */
	public function get_posted_extra_cost( $key ) {
		$extra_cost_rate     = array();
		$extra_cost_rate_keys = array(
			'extra_cost_country_code',
			'extra_cost_name',
			'extra_cost_value'
		);

		foreach ( $extra_cost_rate_keys as $extra_cost_rate_key ) {
			if ( isset( $_POST[ $extra_cost_rate_key ] ) && isset( $_POST[ $extra_cost_rate_key ][ $key ] ) ) {
				$extra_cost_rate[ $extra_cost_rate_key ] = wc_clean( $_POST[ $extra_cost_rate_key ][ $key ] );
			}
		}
		return $extra_cost_rate;
	}
	
	/**
	 * prepare Extra cost rate
	 *
	 * @since 1.0.0
	 */
	private static function prepare_extra_cost( $extra_cost_rate ) {
		foreach ( $extra_cost_rate as $key => $value ) {
			if ( method_exists( __CLASS__, 'format_' . $key ) ) {
				$extra_cost_rate[ $key ] = call_user_func( array( __CLASS__, 'format_' . $key ), $value );
			}
		}
		return $extra_cost_rate;
	}
	
	/**
	 * insert Extra cost rate
	 *
	 * @since 1.0.0
	 */
	public static function _insert_extra_cost( $extra_cost_rate ) {
		global $wpdb;

		$wpdb->insert( $wpdb->prefix . 'woocommerce_extra_cost', self::prepare_extra_cost( $extra_cost_rate ) );
		return $wpdb->insert_id;
	}
	
	/**
	 * delete Extra cost
	 *
	 * @since 1.0.0
	 */
	public static function _delete_extra_cost( $extra_cost_id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_extra_cost WHERE extra_cost_rate_id = %d;", $extra_cost_id ) );
	}
	
	/**
	 * update Extra cost rate
	 *
	 * @since 1.0.0
	 */
	public static function _update_extra_cost( $extra_cost_id, $extra_cost_rate ) {
		global $wpdb;

		$extra_cost_id = absint( $extra_cost_id );

		$wpdb->update(
			$wpdb->prefix . "woocommerce_extra_cost",
			self::prepare_extra_cost( $extra_cost_rate ),
			array(
				'extra_cost_rate_id' => $extra_cost_id
			)
		);
	}
}