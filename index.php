<?php
/*
Plugin Name: Bixxs Veranstaltungen
Plugin URI: https://bixxs.de
Description: Veranstaltungen Enents
Version: 4.0.8
Author: BIXXS
Author URI: https://bixxs.de
License: GPLv3
Text Domain: bixxs_events
Domain Path: /languages
*/

add_action('woocommerce_email_before_order_table', 'bixxs_events_add_content_specific_email', 20, 4);

function bixxs_events_add_content_specific_email($order, $sent_to_admin, $plain_text, $email)
{
	if ($email->id == 'customer_completed_order') {
		echo '<p>Sie können Ihr Ticket oder Gutschein nun herunterladen und ausdrucken, 
			gehen Sie zu 
			<a target="_blank" href="webseite.de/mein-konto/tickets_vouchers/">>>> Mein Konto : <<<</a></p>';
	}
}

//Restrict direct access
if (!defined('ABSPATH')) {
	wp_die("Access not allowed.");
}


require_once(plugin_dir_path(__FILE__) . '/vendor/autoload.php');

if (!defined("BIXXS_EVENTS_PLUGIN_URL")) {
	define("BIXXS_EVENTS_PLUGIN_URL", plugins_url('/', __FILE__));
}

require_once __DIR__ . '/includes/common_functions.php';


// Do stuff on plugin activation
function bixxs_events_activate_plugin()
{
	require_once __DIR__ . '/includes/installer.php';

	$installer = new Installer();
	$installer->run();
}
register_activation_hook(__FILE__, 'bixxs_events_activate_plugin');

//Load Text Domain and include necessary Files
add_action('plugins_loaded', 'bixxs_events_odTextDomainLoaded');
function bixxs_events_odTextDomainLoaded()
{
	require plugin_dir_path(__FILE__) . 'includes/ticketsystem.php';

	load_plugin_textdomain(BIXXS_EVENTS_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . BIXXS_EVENTS_DS . 'languages');
}

//Fix Action Scheduler Error
if (is_admin()) {
	add_action('init', function () {
		delete_option('schema-ActionScheduler_StoreSchema');
	});
}

//Plugin Action Links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'bixxs_events_add_action_links');

function bixxs_events_add_action_links($actions)
{
	$tm_links = array(
		'<a href="' . admin_url('/admin.php?page=event-master') . '">Einstellungen</a>',
	);
	$actions = array_reverse(array_merge($actions, $tm_links));
	return $actions;
}

//Check if WooCommerce is activated
$active_plugins = get_option('active_plugins');

// print_r($active_plugins);
if (in_array('woocommerce/woocommerce.php', $active_plugins)) {
	add_action('init', 'register_bixxs_event_product_type');
} else {
	function mlx_bixxs_events_admin_notice()
	{

		echo '<div class="notice notice-info is-dismissible">
			              <p>Bixxs Events requires to activate the WooCommerce Plugin.</p>
			             </div>';
	}
	add_action('admin_notices', 'mlx_bixxs_events_admin_notice');
}

// New Product Type Code
//Registering New Product Type
function register_bixxs_event_product_type()
{

	class WC_Product_bixxs_events_product extends WC_Product
	{
		protected $product_type = 'bixxs_events_product';

		public function __construct($product)
		{
			$this->product_type = 'bixxs_events_product';
			parent::__construct($product);
		}

		public function get_type()
		{
			return 'bixxs_events_product';
		}
	}
}

add_action("woocommerce_bixxs_events_product_add_to_cart", function () {
	do_action('woocommerce_simple_add_to_cart');
});

add_filter('woocommerce_product_class', 'ig_woocommerce_bixxs_events_product_product_class', 10, 2);
function ig_woocommerce_bixxs_events_product_product_class($classname, $product_type)
{
	if ($product_type == 'bixxs_events_product') {
		$classname = 'WC_Product_bixxs_events_product';
	}
	return $classname;
}


function bixxs_events_save_custom_product_type_terms()
{
	if (!term_exists('bixxs_events_product', 'product_type')) {
		wp_insert_term('bixxs_events_product', 'product_type');
	}
}

add_action('admin_init', 'bixxs_events_save_custom_product_type_terms');


function add_bixxs_events_product_product($types)
{
	// Key should be exactly the same as in the class
	$types['bixxs_events_product'] = 'Veranstaltungen';

	return $types;
}

add_filter('product_type_selector', 'add_bixxs_events_product_product');



function bixxs_events_custom_product_tabs($tabs)
{
	$new_tabs['bixxs_events_product'] = array(
		'label'  => "Veranstaltungen",
		'target' => 'bixxs_events_settings',
		'class'  => array('show_if_bixxs_events_product'),
	);

	$new_tabs['bixxs_events_addon'] = array(
		'label'  => "Veranstaltungen Add-Ons",
		'target' => 'bixxs_events_add_ons',
		'class'  => array('show_if_bixxs_events_product'),
	);

	return array_merge($new_tabs, $tabs);
}

add_filter('woocommerce_product_data_tabs', 'bixxs_events_custom_product_tabs');




function bixxs_events_product_tabs()
{
	global $post, $wpdb;

	require_once __DIR__ . '/includes/product_metadata.php';
}
add_action('woocommerce_product_data_panels', 'bixxs_events_product_tabs');

require_once __DIR__ . '/includes/functions/product_metadata.php';


function bixxs_events_render_addon_field($addon, $loop = 0)
{

?>
	<details open>
		<?php
		echo '<summary><h4>Feld ' . $loop . '</h4></summary>';
		woocommerce_wp_select(
			array(
				'class'      => 'bixxs_events_addons_selection',
				'id'      => 'bixxs_events_field[' . $loop . '][selection]',
				'label'   => 'Art',
				'value'   => $addon['selection'],
				'options' =>  array(
					'number'    => 'Mengenfeld',
					'short'     => 'Textfeld',
					'long'      => 'Textfeld(lang)',
					'dd'        => 'Dropdown',
					'mc'        => 'Multiple Choice',
					'delete'    => 'Löschen',
				)
			)
		);

		$custom_attributes = array(
			'step' => '.01',
			'min' => '0',
		);

		if ($addon['selection'] == 'dd' || $addon['selection'] == 'mc')
			$custom_attributes['readonly'] = 'readonly';

		woocommerce_wp_text_input(
			array(
				'class' => 'bixxs_events_price_per_person',
				'id'      => 'bixxs_events_field[' . $loop . '][price_person]',
				'label' => ($addon['selection'] == 'number') ? 'Preis pro Kundenauswahl' : 'Preis pro Person',
				'type' => 'number',
				'value' => $addon['price_person'],
				'custom_attributes' => $custom_attributes
			)
		);

		woocommerce_wp_text_input(
			array(
				'class' => 'bixxs_events_price_per_event',
				'id'      => 'bixxs_events_field[' . $loop . '][price_event]',
				'label' => 'Preis pro Veranstaltung',
				'type' => 'number',
				'value' => $addon['price_event'],
				'custom_attributes' => $custom_attributes,
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][label]',
				'class' => 'bixxs_events_label',
				'label' => 'Beschriftung',
				'value' => $addon['label'],
				'placeholder' => 'Gast',
			)
		);

		$class = 'bixxs-events-hidden';

		if ($addon['selection'] == 'mc' || $addon['selection'] == 'dd')
			$class = '';

		echo '<div id="bixxs_events_options_' . $loop . '" class="' . $class . '"><h5>Option 1</h5>';

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][1][text]',
				'class' => 'bixxs_events_option',
				'value' => $addon['options'][1]['text'] ?? '',
				'label' => 'Text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][1][price_person]',
				'label' => 'Preis pro Person',
				'type' => 'number',
				'value' => $addon['options'][1]['price_person']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][1][price]',
				'class' => 'bixxs_events_option',
				'label' => 'Preis pro Veranstaltung',
				'type' => 'number',
				'value' => $addon['options'][1]['price']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		echo '<h5>Option 2</h5>';

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][2][text]',
				'class' => 'bixxs_events_option',

				'value' => $addon['options'][2]['text']  ?? '',
				'label' => 'Text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][2][price_person]',
				'label' => 'Preis pro Person',
				'type' => 'number',
				'value' => $addon['options'][2]['price_person']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][2][price]',
				'class' => 'bixxs_events_option',
				'label' => 'Preis pro Veranstaltung',
				'type' => 'number',
				'value' => $addon['options'][2]['price']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);


		echo '<h5>Option 3</h5>';

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][3][text]',
				'class' => 'bixxs_events_option',
				'value' => $addon['options'][3]['text']  ?? '',
				'label' => 'Text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][3][price_person]',
				'label' => 'Preis pro Person',
				'type' => 'number',
				'value' => $addon['options'][3]['price_person']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][3][price]',
				'class' => 'bixxs_events_option',
				'label' => 'Preis pro Veranstaltung',
				'type' => 'number',
				'value' => $addon['options'][3]['price']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);


		echo '<h5>Option 4</h5>';

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][4][text]',
				'class' => 'bixxs_events_option',
				'value' => $addon['options'][4]['text']  ?? '',
				'label' => 'Text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][4][price_person]',
				'label' => 'Preis pro Person',
				'type' => 'number',
				'value' => $addon['options'][4]['price_person']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][4][price]',
				'class' => 'bixxs_events_option',
				'label' => 'Preis pro Veranstaltung',
				'type' => 'number',
				'value' => $addon['options'][4]['price']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);


		echo '<h5>Option 5</h5>';

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][5][text]',
				'class' => 'bixxs_events_option',
				'value' => $addon['options'][5]['text']  ?? '',
				'label' => 'Text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][5][price_person]',
				'label' => 'Preis pro Person',
				'type' => 'number',
				'value' => $addon['options'][5]['price_person']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][5][price]',
				'class' => 'bixxs_events_option',
				'label' => 'Preis pro Veranstaltung',
				'type' => 'number',
				'value' => $addon['options'][5]['price']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);


		echo '<h5>Option 6</h5>';

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][6][text]',
				'class' => 'bixxs_events_option',
				'value' => $addon['options'][6]['text']  ?? '',
				'label' => 'Text',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][6][price_person]',
				'label' => 'Preis pro Person',
				'type' => 'number',
				'value' => $addon['options'][6]['price_person']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'      => 'bixxs_events_field[' . $loop . '][options][6][price]',
				'class' => 'bixxs_events_option',
				'label' => 'Preis pro Veranstaltung',
				'type' => 'number',
				'value' => $addon['options'][6]['price']  ?? '',
				'custom_attributes' => array(
					'step' => '.01',
					'min' => '0',
				)
			)
		);

		?>

		</div>

	</details>

<?php

}

// Update price
add_action('save_post', 'bixxs_events_update_price', 10, 3);
function bixxs_events_update_price($post_id, $post, $update)
{

	if ($post->post_type != 'product') return; // Only products

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;

	$product = wc_get_product($post_id);

	// Check Product Type
	if ($product->get_type() != 'bixxs_events_product')
		return $post_id;

	$price = 0;
	$price += (int) get_post_meta($post_id, 'bixxs_events_price_per_person', true);
	$price += (int) get_post_meta($post_id, 'bixxs_events_price_per_event', true);


	update_post_meta($post_id, '_price', $price); // Update active price
	update_post_meta($post_id, '_regular_price', $price); // Update regular price
	wc_delete_product_transients($post_id); // Update product cache

}

// set price prefix for events
add_filter('woocommerce_get_price_html', 'bixxs_events_add_prefix', 99, 2);

function bixxs_events_add_prefix($price, $product)
{
	if ($product->get_type() != 'bixxs_events_product')
		return $price;

	return 'Ab ' . $price;
}



// Saving data
add_action('woocommerce_process_product_meta', 'bixxs_events_save_options');
function bixxs_events_save_options($product_id)
{
	$product = wc_get_product($product_id);

	$keys = array(
		'bixxs_events_event_template',
		'bixxs_events_start_time',
		'bixxs_events_end_time',
		'bixxs_events_price_per_person',
		'bixxs_events_price_per_event',
		'bixxs_events_label',
		'bixxs_events_label_plural',
		'bixxs_events_max_guests',

	);
	foreach ($keys as $key) {
		if (isset($_POST[$key])) {
			$product->update_meta_data($key, sanitize_text_field($_POST[$key]));
			$product->save();
		}
	}

	// addons
	if (isset($_POST['bixxs_events_field'])) {
		$addons = $_POST['bixxs_events_field'];

		// Sanitize Values
		$sanitized_addons = array();

		$i = 1;
		foreach ($addons as $key => $addon) {
			// Check if price and name is set
			if (!isset($addon['selection']) || $addon['selection'] == 'delete' || $key == '0')
				continue;

			$sanitized_addons[$i]['selection'] = sanitize_text_field($addon['selection']);
			$sanitized_addons[$i]['price_person'] = max((float)$addon['price_person'], 0);
			$sanitized_addons[$i]['price_event'] = max((float)$addon['price_event'], 0);
			$sanitized_addons[$i]['label'] = sanitize_text_field($addon['label']);

			// Sanitize options for MC and DD
			if ($addon['selection'] == 'dd' || $addon['selection'] == 'mc') {
				$options = $addon['options'];
				$sanitized_options = array();

				$j = 1;

				foreach ($options as $option) {
					$sanitized_option = array();

					if (!isset($option['text']) || $option['text'] == '') {
						continue;
					}
					$sanitized_option['text'] = sanitize_text_field($option['text']);

					if (isset($option['price_person'])) {
						$sanitized_option['price_person'] = max((float)$option['price_person'], 0);
					}

					if (isset($option['price'])) {
						$sanitized_option['price'] = max((float)$option['price'], 0);
					}

					if (!empty($sanitized_option)) {
						$sanitized_options[$j] = $sanitized_option;
						$j++;
					}
				}

				$sanitized_addons[$i]['options'] = $sanitized_options;
			}

			$i++;
		}

		if (count($sanitized_addons) < 1) {
			$product->delete_meta_data('bixxs_events_fields');
		} else {
			$product->update_meta_data('bixxs_events_fields', json_encode($sanitized_addons));
		}
		$product->save();
	} else {
		$product->delete_meta_data('bixxs_events_field');
		$product->save();
	}
}

require_once __DIR__ . '/includes/functions/availability.php';


function bixxs_events_enqueue_variation_scritp()
{
	wp_enqueue_script('bixxs_events_variation', plugin_dir_url(__FILE__) . 'admin/js/addons.js', '', '1.71');
}
add_action('admin_enqueue_scripts', 'bixxs_events_enqueue_variation_scritp');

/*
        }else{
            if(!empty($licenseKey) && !empty($this->licenseMessage)){
               $this->showMessage=true;
            }
            update_option("Veranstaltungen_lic_Key","") || add_option("Veranstaltungen_lic_Key","");
            add_action( 'admin_post_Veranstaltungen_el_activate_license', [ $this, 'action_activate_license' ] );
            add_action( 'admin_menu', [$this,'InactiveMenu']);
        }
    }
    function SetAdminStyle() {
        wp_register_style( "VeranstaltungenLic", plugins_url("_lic_style.css",$this->plugin_file),10);
        wp_enqueue_style( "VeranstaltungenLic" );
    }
    function ActiveAdminMenu(){
        
	//add_menu_page (  "Veranstaltungen", "Veranstaltungen", "activate_plugins", $this->slug, [$this,"Activated"], "f508");
	add_submenu_page(  $this->slug, "Veranstaltungen License", "License Info", "activate_plugins",  $this->slug."_license", [$this,"Activated"] );
	
    }
    function InactiveMenu() {
        add_menu_page( "Veranstaltungen", "Veranstaltungen", 'activate_plugins', $this->slug,  [$this,"LicenseForm"], "f508" );

    }
    function action_activate_license(){
        check_admin_referer( 'el-license' );
        $licenseKey=!empty($_POST['el_license_key'])?$_POST['el_license_key']:"";
        $licenseEmail=!empty($_POST['el_license_email'])?$_POST['el_license_email']:"";
        update_option("Veranstaltungen_lic_Key",$licenseKey) || add_option("Veranstaltungen_lic_Key",$licenseKey);
        update_option("Veranstaltungen_lic_email",$licenseEmail) || add_option("Veranstaltungen_lic_email",$licenseEmail);
        update_option('_site_transient_update_plugins','');
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }
    function action_deactivate_license() {
        check_admin_referer( 'el-license' );
        $message="";
        if(C0C7D3365::RemoveLicenseKey(__FILE__,$message)){
            update_option("Veranstaltungen_lic_Key","") || add_option("Veranstaltungen_lic_Key","");
            update_option('_site_transient_update_plugins','');
        }
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }
    function Activated(){
        ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="Veranstaltungen_el_deactivate_license"/>
            <div class="el-license-container">
                <h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("Veranstaltungen License Info",$this->slug);?> </h3>
                <hr>
                <ul class="el-license-info">
                <li>
                    <div>
                        <span class="el-license-info-title"><?php _e("Status",$this->slug);?></span>

                        <?php if ( $this->responseObj->is_valid ) : ?>
                            <span class="el-license-valid"><?php _e("Valid",$this->slug);?></span>
                        <?php else : ?>
                            <span class="el-license-valid"><?php _e("Invalid",$this->slug);?></span>
                        <?php endif; ?>
                    </div>
                </li>

                <li>
                    <div>
                        <span class="el-license-info-title"><?php _e("License Type",$this->slug);?></span>
                        <?php echo $this->responseObj->license_title; ?>
                    </div>
                </li>

               <li>
                   <div>
                       <span class="el-license-info-title"><?php _e("License Expired on",$this->slug);?></span>
                       <?php echo $this->responseObj->expire_date;
                       if(!empty($this->responseObj->expire_renew_link)){
                           ?>
                           <a target="_blank" class="el-blue-btn" href="<?php echo $this->responseObj->expire_renew_link; ?>">Renew</a>
                           <?php
                       }
                       ?>
                   </div>
               </li>

               <li>
                   <div>
                       <span class="el-license-info-title"><?php _e("Support Expired on",$this->slug);?></span>
                       <?php
                           echo $this->responseObj->support_end;
                        if(!empty($this->responseObj->support_renew_link)){
                            ?>
                               <a target="_blank" class="el-blue-btn" href="<?php echo $this->responseObj->support_renew_link; ?>">Renew</a>
                            <?php
                        }
                       ?>
                   </div>
               </li>
                <li>
                    <div>
                        <span class="el-license-info-title"><?php _e("Your License Key",$this->slug);?></span>
                        <span class="el-license-key"><?php echo esc_attr( substr($this->responseObj->license_key,0,9)."XXXXXXXX-XXXXXXXX".substr($this->responseObj->license_key,-9) ); ?></span>
                    </div>
                </li>
                </ul>
                <div class="el-license-active-btn">
                    <?php wp_nonce_field( 'el-license' ); ?>
                    <?php submit_button('Deactivate'); ?>
                </div>
            </div>
        </form>
    <?php
    }

    function LicenseForm() {
        ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="Veranstaltungen_el_activate_license"/>
        <div class="el-license-container">
            <h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("Veranstaltungen Licensing",$this->slug);?></h3>
            <hr>
            <?php
            if(!empty($this->showMessage) && !empty($this->licenseMessage)){
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo _e($this->licenseMessage,$this->slug); ?></p>
                </div>
                <?php
            }
            ?>
            <h1>Sie brauchen Hilfe ?</h1><p>M.Lenuweit<br />lenuweit@pos-software.de<br />Tel.: 02842-909100<br />Handy+WhatsApp 0176 579 500 20</p>
            <div class="el-license-field">
                <label for="el_license_key"><?php _e("License code",$this->slug);?></label>
                <input type="text" class="regular-text code" name="el_license_key" size="50" placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" required="required">
            </div>
            <div class="el-license-field">
                <label for="el_license_key"><?php _e("Email Address",$this->slug);?></label>
                <?php
                    $purchaseEmail   = get_option( "Veranstaltungen_lic_email", get_bloginfo( 'admin_email' ));
                ?>
                <input type="text" class="regular-text code" name="el_license_email" size="50" value="<?php echo $purchaseEmail; ?>" placeholder="" required="required">
                <div><small><?php _e("We will send update news of this product by this email address, don't worry, we hate spam",$this->slug);?></small></div>
            </div>
            <div class="el-license-active-btn">
                <?php wp_nonce_field( 'el-license' ); ?>
                <?php submit_button('Activate'); ?>
            </div>
        </div>
    </form>
        <?php
    }
}

new Veranstaltungen_M0C7D3365();*/