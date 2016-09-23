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
		wp_enqueue_style( 'wp-pointer' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-extra-cost-admin.js', array( 'jquery', 'jquery-ui-dialog' ), $this->version, false );
		wp_enqueue_script( 'wp-pointer' );
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
		woocommerce_wp_checkbox(array('id' => '_extra_cost_enable_qty', 'label' => __('Enable/Disable', 'woocommerce-extra-cost'), 'description' => __('Enable Extra Cost based on quantity', 'woocommerce-extra-cost')));

		woocommerce_wp_text_input(array('id' => '_extra_cost_label', 'label' => __('Extra Cost Label', 'woocommerce-extra-cost'), 'description' => __('Extra Cost label ', 'woocommerce-extra-cost'), 'class' => 'short'));
		woocommerce_wp_text_input(array('id' => '_extra_cost_amount', 'label' => __('Extra Cost amount', 'woocommerce-extra-cost'), 'description' => __('Extra Cost per Product', 'woocommerce-extra-cost'), 'class' => 'short wc_input_price'));

		woocommerce_wp_text_input(array('id' => '_extra_cost_amount_quatity', 'label' => __('Extra Cost +1 quantity', 'woocommerce-extra-cost'), 'description' => __('Extra Cost per Product quantity', 'woocommerce-extra-cost'), 'class' => 'short wc_input_price_quantity'));

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

		if (isset($_POST['_extra_cost_enable_qty'])) {
			update_post_meta($post_id, '_extra_cost_enable_qty', $_POST['_extra_cost_enable_qty']);
		} else {
			update_post_meta($post_id, '_extra_cost_enable_qty', '');
		}

		if (isset($_POST['_extra_cost_label'])) {
			update_post_meta($post_id, '_extra_cost_label', $_POST['_extra_cost_label']);
		}

		if (isset($_POST['_extra_cost_amount'])) {
			update_post_meta($post_id, '_extra_cost_amount', $_POST['_extra_cost_amount']);
		}
		if(isset($_POST['_extra_cost_amount_quatity']) && !empty($_POST['_extra_cost_amount_quatity']))
		{
			update_post_meta($post_id,'_extra_cost_amount_quatity',$_POST['_extra_cost_amount_quatity']);
			update_post_meta($post_id, '_extra_cost_enable_qty', '');
		}
		if(empty($_POST['_extra_cost_amount_quatity']) && empty($_POST['_extra_cost_enable_qty']) )
		{
			update_post_meta($post_id, '_extra_cost_enable_qty', '');
			update_post_meta($post_id,'_extra_cost_amount_quatity', '');
		}
		if(empty($_POST['_extra_cost_amount_quatity']) && !empty($_POST['_extra_cost_enable_qty']))
		{
			update_post_meta($post_id, '_extra_cost_enable_qty', $_POST['_extra_cost_enable_qty']);
			update_post_meta($post_id,'_extra_cost_amount_quatity', '');
		}

	}

	public function woo_extra_cost_admin_init_own(){
		require_once 'partials/woo-extra-cost-admin-display.php';
		$admin = new WC_Settings_Extra_Cost();
	}

	public function welcome_screen_pages_extra_cost() {

		add_dashboard_page('Welcome To WooCommerce Extra Cost','Welcome To WooCommerce Extra Cost', 'read','woo-extra-cost-about',array($this,'welcome_screen_content_extra_cost' ));

	}
	
	public function welcome_screen_content_extra_cost ( ){ ?>
		<div class="wrap about-wrap">
            <h1 style="font-size: 2.1em;"><?php printf(__('Welcome to WooCommerce Extra Cost', 'woo-extra-cost')); ?></h1>

            <div class="about-text woocommerce-about-text">
        <?php
        $message = '';
        printf(__('%s This plugin allows store owner to add extra cost based on product as well as based on country selected.', 'woo-extra-cost'), $message, $this->version);
        ?>
                <img class="version_logo_img" src="<?php echo plugin_dir_url(__FILE__) . 'images/woo-extra-cost.png'; ?>">
            </div>

        <?php
        $setting_tabs_wc = apply_filters('woocommerce_extra_cost_setting_tab', array("about" => "Overview", "other_plugins" => "Checkout our other plugins" , "premium_feauter" =>"Premium Feature"));
        $current_tab_wc = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
        $aboutpage = isset($_GET['page'])
        ?>
            <h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs_wc as $name => $label)
            echo '<a  href="' . home_url('wp-admin/index.php?page=woo-extra-cost-about&tab=' . $name) . '" class="nav-tab ' . ( $current_tab_wc == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
            </h2>
                <?php
                foreach ($setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue) {
                	switch ($setting_tabkey_wc) {
                		case $current_tab_wc:
                			do_action('woocommerce_extra_cost_' . $current_tab_wc);
                			break;
                	}
                }
                ?>
            <hr />
            <div class="return-to-dashboard">
                <a href="<?php echo home_url('/wp-admin/admin.php?page=wc-settings&tab=extra_cost_settings'); ?>"><?php _e('Go to WooCommerce Extra Cost Settings', 'woo-extra-cost'); ?></a>
            </div>
        </div>
        
        <?php 
         $current_user = wp_get_current_user();
        if (!get_option('wcec_plugin_notice_shown')) {
   			 echo '<div id="wcec_dialog" title="Basic dialog"><p> Subscribe for latest plugin update and get notified when we update our plugin and launch new products for free! </p> <p><input type="text" id="txt_user_sub_wcec" class="regular-text" name="txt_user_sub_wcec" value="'.$current_user->user_email.'"></p></div>';
   			
		?>
		
		 <script type="text/javascript">

        jQuery( document ).ready(function() {
        	jQuery( "#wcec_dialog" ).dialog({
        		modal: true, title: 'Subscribe Now', zIndex: 10000, autoOpen: true,
        		width: '450', resizable: false,
        		position: {my: "center", at:"center", of: window },
        		buttons: {
        			Yes: function () {
        				// $(obj).removeAttr('onclick');
        				// $(obj).parents('.Parent').remove();
        				var email_id = jQuery('#txt_user_sub_wcec').val();

        				var data = {
        				'action': 'add_plugin_user_wcec',
        				'email_id': email_id
        				};

        				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        				jQuery.post(ajaxurl, data, function(response) {
        					jQuery('#wcec_dialog').html('<h2>You have been successfully subscribed');
        					jQuery(".ui-dialog-buttonpane").remove();
        				});


        				//jQuery(this).dialog("close");
        			},
        			No: function () {
        				
        				var email_id = jQuery('#txt_user_sub_wcec').val();

        				var data = {
        				'action': 'hide_subscribe',
        				'email_id': email_id
        				};

        				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        				jQuery.post(ajaxurl, data, function(response) {
        					        					
        				});
        				
        				jQuery(this).dialog("close");
        				
        			}
        		},
        		close: function (event, ui) {
        			jQuery(this).remove();
        		}
        	});
        });
        </script>
		<?php } ?>
    <?php     
	
	}
	
	public function woocommerce_extra_cost_about() {
	   $current_user = wp_get_current_user();
			?>
	  	<div class="changelog">
            </br>
           	<style type="text/css">
				p.woocommerce_extra_cost_overview {max-width: 100% !important;margin-left: auto;margin-right: auto;font-size: 15px;line-height: 1.5;}.woocommerce_extra_cost_content_ul ul li {margin-left: 3%;list-style: initial;line-height: 23px;}
			</style>  
            <div class="changelog about-integrations">
                <div class="wc-feature feature-section col three-col">
                    <div>
                        <p class="woocommerce_extra_cost_overview"><?php _e('Easily add extra cost to particular product as well as based on country selected on checkout page.', 'woo-extra-cost'); ?></p>
                     
                         <div class="woocommerce_extra_cost_content_ul">
                        	<ul>
								<li>Easy setup no specialization required to use.</li>
								<li>User friendly interface.</li>
								<li>Add label for extra cost to product.</li>
								<li>Add extra cost to particular product.</li>
								<li>Add country code and cost for that country to apply the extra cost to that country.</li>
							</ul>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
		  
	     
	        
	  
  <?php
} 

	public function woocommerce_extra_cost_other_plugins ( ){
	
		global $wpdb;
         $url = 'http://www.multidots.com/store/wp-content/themes/business-hub-child/API/checkout_other_plugin.php';
    	 $response = wp_remote_post( $url, array('method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => array('plugin' => 'advance-flat-rate-shipping-method-for-woocommerce'),
    	'cookies' => array()));
    	
    	$response_new = array();
    	$response_new = json_decode($response['body']);
		$get_other_plugin = maybe_unserialize($response_new);
		
		$paid_arr = array();
		?>

        <div class="plug-containter">
        	<div class="paid_plugin">
        	<h3>Paid Plugins</h3>
	        	<?php foreach ($get_other_plugin as $key=>$val) { 
	        		if ($val['plugindesc'] =='paid') {?>
	        			
	        			
	        		   <div class="contain-section">
	                <div class="contain-img"><img src="<?php echo $val['pluginimage']; ?>"></div>
	                <div class="contain-title"><a target="_blank" href="<?php echo $val['pluginurl'];?>"><?php echo $key;?></a></div>
	            </div>	
	        			
	        			
	        		<?php }else {
	        			
	        			$paid_arry[$key]['plugindesc']= $val['plugindesc'];
	        			$paid_arry[$key]['pluginimage']= $val['pluginimage'];
	        			$paid_arry[$key]['pluginurl']= $val['pluginurl'];
	        			$paid_arry[$key]['pluginname']= $val['pluginname'];
	        		
	        	?>
	        	
	         
	            <?php } }?>
           </div>
           <?php if (isset($paid_arry) && !empty($paid_arry)) {?>
           <div class="free_plugin">
           	<h3>Free Plugins</h3>
                <?php foreach ($paid_arry as $key=>$val) { ?>  	
	            <div class="contain-section">
	                <div class="contain-img"><img src="<?php echo $val['pluginimage']; ?>"></div>
	                <div class="contain-title"><a target="_blank" href="<?php echo $val['pluginurl'];?>"><?php echo $key;?></a></div>
	            </div>
	            <?php } }?>
           </div>
          
        </div>

	<?php } 
	
	public  function woocommerce_extra_cost_premium_feauter ( ){ ?>
		<div class="changelog">
            </br>
           	<style type="text/css">
				p.woocommerce_extra_cost_overview {max-width: 100% !important;margin-left: auto;margin-right: auto;font-size: 15px;line-height: 1.5;}.woocommerce_extra_cost_content_ul ul li {margin-left: 3%;list-style: initial;line-height: 23px;}
			</style>  
            <div class="changelog about-integrations">
                <div class="wc-feature feature-section col three-col">
                    <div>
                        <p class="woocommerce_extra_cost_overview"><strong>Woocommerce Conditional Extra Fees</strong></p>  
                        <p class="woocommerce_extra_cost_overview">Need even more? upgrade to <a href="https://codecanyon.net/item/woocommerce-save-for-later/17421044?s_rank=2" rel="nofollow">Woocommerce Conditional Extra Fees</a> and get all the features available in Woocommerce Conditional Extra Fees</p> 

                        <p class="woocommerce_extra_cost_overview"><strong>This plugin  allows store owners to add extra fixed charges/fees to the customer's order based on different conditions. You can configure product specific, category specific, country specific or order amount specific extra charges and it will be applicable to entire order. The charges will be added to the cart total.</strong></p>

                       
                        <p class="woocommerce_extra_cost_overview"><strong>List of conditional extra fees with examples: </strong></p>
                        
                        <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra fees based on Country</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra fees based on country. For example, you can charge extra cost based on "Country" like India= $5. Ex. If user purchase products from the India then extra $5 cost will be added to the cart total.</p>
							</div>
						 </div>
						 
						 
						 <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based on User Role</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on User Role.(like Author,Subscriber). For example, if we select user role "author" = $30 extra cost. When "author" add product in to the cart then extra $30 cost will be added to the total cart.</p>
							</div>
						 </div>
						 
						  <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based Shipping Class</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on Shipping Classes. You can view all product shipping class here. For example, Let's say shipping class "Large" = $20 extra cost. When the customer adds "Large" shipping class product into the cart then extra $20 cost will be added into the cart.</p>
							</div>
						 </div>
						 
						 <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Remove All Extra Fees based on Max Total</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can remove all extra cost based on Maximum Cart Total Amount. For example, if we specify maximum cart total amount = $5000 and above then remove all extra cost. When customer cart total amounts $5000 and above then remove all extra cost from the current cart.</p>
							</div>
						 </div>
						 
						 <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based on Cart Total</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on maximum and minimum Cart Total value. For example, if we specify cart total value greater than or equal to (>=) $500 then charge Extra cost $20 and if cart total less than or equal to (>=) $100 then charge Extra cost $30. When the customer cart total amount is $500 and above then Extra $20 cost will be added to the cart total. OR if cart total amount is $100 and less then Extra $30 cost will be added to the cart total.</p>
							</div>
						 </div>
						 
						 
						 <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based on Total Cart Quantity</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on maximum and minimum cart total quantity. For example, if we specify Cart Total Quantity greater than or equal to (>=) 20 then charge Extra cost $20 and if cart total Quantity less than or equal to (>=) 10 then charge Extra cost $10. When the customer cart total Quantity is 22 and above then Extra $20 cost will be added to the cart total. OR if cart total Quantity is 8 and less then Extra $10 cost will be added to the cart total.</p>
							</div>
						 </div>
						 
						  <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based on Cart Weight</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on maximum and minimum cart total weight. For example, if we specify Cart Total Weight greater than or equal to (>=) 500 lbs then charge Extra cost $50 and if cart total weight less than or equal to (>=) 300 lbs then charge Extra cost $20. When the customer cart total weight is 550 lbs and above then Extra $50 cost will be added to the cart total. OR if cart total weight is 250 lbs and less then Extra $20 cost will be added to the cart total.</p>
							</div>
						 </div>
						 
						 
						  <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based on Product</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Fees based on Product. you can see all product here. For example, if we select product "beach T-shirt" = $20 extra cost. When the customer adds "beach T-shirt" product into the cart then extra $20 cost will be added into the cart total.</p>
							</div>
						 </div>
						 
						 
						  <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees enable for Variable Product</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">If variable Product is "Enable" then you can add "Extra Cost Message" and "Cost". When customer will add this product in to the cart at that time extra cost charge is automatically added in to cart total. For example, You have varitions for product with Red and Blue colour. if we specify Red = $10 and Blue= $20 extra charge. When customer will add "red color product" in to the cart and "extra $10 cost" will be added in the cart total.</p>
							</div>
						 </div>
						 
						  <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based on Product Category</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on Product Category. you can view all category here. For example, if we select "Book" category = $30 extra cost. When the customer adds "xyz book" from book category into the cart then extra $30 cost will be added into the total cart.</p>
							</div>
						 </div>
						 
						 <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees based on Product Tag</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on Product Tag. you can view all product tag here. For example, if we specify Product Tag "SPECIAL011" = $15 extra cost. When the customer adds product with product tag "SPECIAL011" into the cart then extra $15 cost will be added to the total cart.</p>
							</div>
						 </div>
						 
						 
						  <div class="woocommerce_extra_cost_picture_entry_content">
							<div class="woocommerce_extra_cost_premium_feature_list">
								<p class="woocommerce_extra_cost_overview"><strong>Extra Fees on Particular Coupon</strong></p>
							</div>	
							<div class="woocommerce_extra_cost_feature_list">
								<p class="woocommerce_extra_cost_overview">Using this feature you can charge Extra Cost based on Product Coupon. you can view all coupons here. For example, if we specify Coupon "Code011" = $15 extra cost. When the customer apply coupon "Code011" into the cart then extra $15 cost will be added to the total cart.</p>
							</div>
						 </div>
						 
                        
                    </div>
                </div>
            </div>
        </div>			
		
	<?php }
function welcome_screen_remove_menus() {
    remove_submenu_page( 'index.php', 'woo-extra-cost-about' );
}
 public function welcome_screen_do_activation_redirect_extra_cost() {
  // Bail if no activation redirect
    if ( ! get_transient( '_woo_extra_cost_welcome_screen' ) ) {
    return;
  }

  // Delete the redirect transient
  delete_transient( '_woo_extra_cost_welcome_screen' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

  // Redirect to bbPress about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'woo-extra-cost-about&tab=about' ), admin_url( 'index.php' ) ) );

} 
function welcome_screen_remove_menus_extra_cost() {
    remove_submenu_page( 'index.php', 'woo-extra-cost-about' );
}

function my_dismiss_extra_cost_notice() {
	update_option( 'woo-extra-cost-notice-dismissed','1' );
	
} 

public function woocommerce_extra_cost_pointers_footer () { 
	$admin_pointers = woocommerce_extra_cost_admin_pointers();
	    ?>
	    <script type="text/javascript">
	        /* <![CDATA[ */
	        ( function($) {
	            <?php
	            foreach ( $admin_pointers as $pointer => $array ) {
	               if ( $array['active'] ) {
	                  ?>
	            $( '<?php echo $array['anchor_id']; ?>' ).pointer( {
	                content: '<?php echo $array['content']; ?>',
	                position: {
	                    edge: '<?php echo $array['edge']; ?>',
	                    align: '<?php echo $array['align']; ?>'
	                },
	                close: function() {
	                    $.post( ajaxurl, {
	                        pointer: '<?php echo $pointer; ?>',
	                        action: 'dismiss-wp-pointer'
	                    } );
	                }
	            } ).pointer( 'open' );
	            <?php
	         }
	      }
	      ?>
	        } )(jQuery);
	        /* ]]> */
	    </script> <?php 

} 



 public function admin_notices_extra_cost() {
  ?>
 <div class="notice error woo-extra-cost-notice is-dismissible" >
        <div><p><?php _e( 'You are currently using the free version of WooCommerce Extra Cost. To enjoy extra features, buy the Pro version <a href="http://codecanyon.net/item/advance-extra-cost-plugin-for-woocommerce/16351490g" target="_blank">
  Advance Extra Cost for WooCommerce</a>', 'my-text-domain' ); ?></p></div>
    </div>
  <?php
}
public function wp_add_plugin_userfn_wcec() {
    	$email_id= $_POST['email_id'];
    	$log_url = $_SERVER['HTTP_HOST'];
    	$cur_date = date('Y-m-d');
    	$url = 'http://www.multidots.com/store/wp-content/themes/business-hub-child/API/wp-add-plugin-users.php';
    	$response = wp_remote_post( $url, array('method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => array('user'=>array('plugin_id' => '2','user_email'=>$email_id,'plugin_site' => $log_url,'status' => 1,'activation_date'=>$cur_date)),
    	'cookies' => array()));
		update_option('wcec_plugin_notice_shown', 'true');
    }
    
    public function hide_subscribe_own() {
    	$email_id= $_POST['email_id'];
		update_option('wcec_plugin_notice_shown', 'true');
    }


}

function woocommerce_extra_cost_admin_pointers () { 
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
    $version = '1_0'; // replace all periods in 1.0 with an underscore
    $prefix = 'woocommerce_extra_cost_admin_pointers_notice' . $version . '_';

    $new_pointer_content = '<h3>' . __( 'Welcome to WooCommerce Extra Cost' ) . '</h3>';
    $new_pointer_content .= '<p>' . __( 'This plugin allows store owner to add extra cost based on product as well as based on country selected.' ) . '</p>';

    return array(
        $prefix . 'woocommerce_extra_cost_admin_pointers_notice' => array(
            'content' => $new_pointer_content,
            'anchor_id' => '#toplevel_page_woocommerce',
            'edge' => 'left',
            'align' => 'left',
            'active' => ( ! in_array( $prefix . 'woocommerce_extra_cost_admin_pointers_notice', $dismissed ) )
        )
    );
}