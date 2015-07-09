<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Implements features of YITH WooCommerce Catalog Mode plugin
 *
 * @class   YITH_WC_Catalog_Mode
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */
class YITH_WC_Catalog_Mode_Premium extends YITH_WC_Catalog_Mode {

    /**
     * Constructor
     *
     * Initialize plugin and registers actions and filters to be used
     *
     * @since  1.0
     * @author Alberto Ruggiero
     */
    public function __construct() {
        if ( ! function_exists( 'WC' ) ) {
            return;
        }

        parent::__construct();

        $this->includes();

        YITH_Icon();


        if ( class_exists( 'YWCTM_Exclusions_Table' ) ) {

            add_filter( 'set-screen-option', 'YWCTM_Exclusions_Table::set_options', 10, 3);

            add_action( 'ywctm_exclusions', 'YWCTM_Exclusions_Table::output' );

            add_action( 'current_screen', 'YWCTM_Exclusions_Table::add_options' );

        }


        add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
        add_action( 'woocommerce_process_product_meta', 'YWCTM_Meta_Box::save', 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_premium_styles_admin' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_premium_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_premium_scripts' ) );
        add_action( 'woocommerce_admin_field_icon', 'YITH_Icon_List::output' );

        if ( get_option( 'ywctm_enable_plugin' ) == 'yes' && !( current_user_can( 'administrator' ) && is_user_logged_in() &&  get_option( 'ywctm_admin_view' ) == 'no' ) ){

                if ( ! is_admin() ) {

                    add_filter( 'woocommerce_product_tabs', array( $this, 'add_inquiry_form_tab' ) );
                    add_filter( 'woocommerce_product_tabs', array( $this, 'disable_reviews_tab' ), 98 );
                    add_filter( 'woocommerce_get_price_html', array( $this, 'show_product_price' ) );

                    add_action( 'woocommerce_single_product_summary', array( $this, 'hide_product_price_single' ), 5 );
                    add_action( 'woocommerce_single_product_summary', array( $this, 'show_custom_button' ), 20 );
                    add_action( 'woocommerce_after_shop_loop_item', array( $this, 'show_custom_button_loop' ), 20 );

                    add_action( 'wp_head', array( $this, 'custom_button_css' ) );
                }

        }

        add_filter( 'yit_get_contact_forms', array( $this, 'yit_get_contact_forms' ) );
        add_filter( 'wpcf7_get_contact_forms', array( $this, 'wpcf7_get_contact_forms' ) );

        // register plugin to licence/update system
        add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
        add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
    }

    /**
     * Files inclusion
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    private function includes() {

        include_once( 'includes/class-yith-icon.php' );

        if ( is_admin() ) {
            include_once( 'includes/admin/class-yith-custom-table.php' );
            include_once( 'includes/admin/meta-boxes/class-ywctm-meta-box.php' );
            include_once( 'templates/admin/exclusions-table.php' );
            include_once( 'templates/admin/icon-list.php' );
        }

    }

    /**
     * Enqueue css file
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    public function enqueue_premium_styles_admin() {

        wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array() );
        wp_enqueue_style( 'yit-style', YWCTM_ASSETS_URL . 'css/yith-catalog-mode-premium-admin.css' );

    }

    /**
     * Enqueue css file
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    public function enqueue_premium_styles() {

        wp_enqueue_style( 'ywctm-premium-style', YWCTM_ASSETS_URL . 'css/yith-catalog-mode-premium.css' );

    }

    /**
     * Enqueue script file
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    public function enqueue_premium_scripts() {

        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ) );
        wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'woocommerce_admin' );
        wp_enqueue_script( 'ywctm-admin', YWCTM_ASSETS_URL . 'js/ywctm-admin.js', array( 'jquery' ), false, true );

    }

    /**
     * Removes reviews tab from single page product
     *
     * @param array $tabs
     * @return array
     * @since 1.0.0
     * @author Alberto Ruggiero
     */
    public function disable_reviews_tab( $tabs ) {

        if ( ( get_option( 'ywctm_disable_review' ) == 'unregistered' && ! is_user_logged_in() ) || get_option( 'ywctm_disable_review' ) == 'all' ){
            unset( $tabs['reviews'] );
        }

        return $tabs;
    }

    /**
     * Get list of forms by YIT Contact Form plugin
     *
     * @param   $array array
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  array
     */
    public function yit_get_contact_forms( $array = array() ) {
        if( ! function_exists( 'YIT_Contact_Form' ) ){
            return array( '' => __( 'Plugin not activated or not installed', 'ywctm' ) );
        }

        $posts = get_posts( array(
            'post_type' => YIT_Contact_Form()->contact_form_post_type
        ) );

        foreach( $posts as $post ){
            $array[ $post->post_name ] = $post->post_title;
        }

        if( $array == array() ) return array( '' => __( 'No contact form found', 'ywctm' ) );

        return $array;
    }

    /**
     * Get list of forms by Contact Form 7 plugin
     *
     * @param   $array array
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  array
     */
    public function wpcf7_get_contact_forms( $array = array() ) {
        if( ! function_exists( 'wpcf7_contact_form' ) ){
            return array( '' => __( 'Plugin not activated or not installed', 'ywctm' ) );
        }

        $posts = WPCF7_ContactForm::find();

        foreach( $posts as $post ){
            $array[ $post->id() ] = $post->title();
        }

        if( $array == array() ) return array( '' => __( 'No contact form found', 'ywctm' ) );

        return $array;
    }

    /**
     * Add inquiry form tab to single page product
     *
     * @param array $tabs
     * @return array
     * @since 1.0.0
     * @author Alberto Ruggiero
     */
    public function add_inquiry_form_tab( $tabs = array() ) {

        if ( get_option( 'ywctm_inquiry_form_type' ) != 'none' && ( function_exists( 'YIT_Contact_Form' ) || function_exists( 'wpcf7_contact_form' ) ) ) {

            global $post;

            $show_yit_contact_form = ( get_option( 'ywctm_inquiry_form_type' ) == 'yit-contact-form' && get_option( 'ywctm_inquiry_yit_contact_form_id' ) != '' );
            $show_contact_form_7   = ( get_option( 'ywctm_inquiry_form_type' ) == 'contact-form-7' && get_option( 'ywctm_inquiry_contact_form_7_id' ) != '' );

            if ( $show_yit_contact_form || $show_contact_form_7 ) {

                $tabs['inquiry_form'] = array(
                    'title'    => __( 'Inquiry form', 'ywctm' ),
                    'priority' => 40,
                    'callback' => array( $this, 'get_inquiry_form' )
                );
            }
        }

        return $tabs;
    }

    /**
     * Inquiry form tab template
     *
     * @since   1.0.0
     * @return  string
     * @author  Alberto ruggiero
     */
    public function get_inquiry_form() {

        switch ( get_option( 'ywctm_inquiry_form_type' ) ){
            case 'yit-contact-form':
                $shortcode = '[contact_form name="' . get_option( 'ywctm_inquiry_yit_contact_form_id' ) .'"]';
                break;
            case 'contact-form-7':
                $shortcode = '[contact-form-7 id="' . get_option( 'ywctm_inquiry_contact_form_7_id' ) .'"]';
                break;
        }

        echo do_shortcode( $shortcode );

    }

    /**
     * Add a metabox on product page
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    public function add_metabox() {
        add_meta_box( 'yctm-metabox', __( 'Catalog Mode Exclusions', 'ywctm' ), 'YWCTM_Meta_Box::output', 'product', 'normal', 'high' );
    }

    /**
     * Add a custom button in product details page
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  string
     */
    public function show_custom_button() {

        if ( get_option( 'ywctm_custom_button' ) == 'yes' && $this->check_add_to_cart_single()  ){

            $this->get_custom_button_template();

        }

    }

    /**
     * Add a custom button in loop
     *
     * @since   1.0.4
     * @author  Alberto Ruggiero
     * @return  string
     */
    public function show_custom_button_loop() {

        if ( get_option( 'ywctm_custom_button_loop' ) == 'yes' && $this->check_add_to_cart_single() ){

            $this->get_custom_button_template();

        }

    }

    /**
     * Get custom button template
     *
     * @since   1.0.4
     * @author  Alberto Ruggiero
     * @return  string
     */
    public function get_custom_button_template() {

        $button_text        = get_option( 'ywctm_button_text' );
        $button_url_type    = get_option( 'ywctm_button_url_type' ) == 'generic' ? '' : get_option( 'ywctm_button_url_type' ) . ':';
        $button_url         = get_option( 'ywctm_button_url' ) == '' ? '#' : get_option( 'ywctm_button_url' );
        $icon               = get_option( 'ywctm_button_icon' );

        ?>
        <div id="custom-button">
            <p>
                <a class="button ywctm-custom-button" href="<?php printf( '%s%s', $button_url_type, $button_url ); ?>">
                    <?php
                    switch ($icon['select']) :
                        case 'icon': ?>
                            <span class="icon-form" <?php echo YITH_Icon()->get_icon_data( $icon['icon'] ) ?>></span>
                            <?php break;
                        case 'custom': ?>
                            <span class="custom-icon"><img src="<?php echo esc_url( $icon['custom'] ); ?>"></span>
                            <?php break;
                    endswitch;?>
                    <span class="inquiry-title"><?php echo $button_text; ?></span>
                </a>
            </p>
        </div>
    <?php

    }

    /**
     * Set custom css for custom button
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  string
     */
    public function custom_button_css() {

        $button_color = get_option( 'ywctm_button_color' );
        $button_hover_color = get_option( 'ywctm_button_hover' );

        if ( $button_color != '' || $button_hover_color != '') : ?>
            <style type="text/css">
                <?php if ( $button_color != '' ) : ?>
                .ywctm-custom-button { color: <?php echo $button_color; ?> !important; }
                <?php endif;
                if ( $button_hover_color != '') :?>
                .ywctm-custom-button:hover { color:  <?php echo $button_hover_color; ?> !important; }
                <?php endif; ?>
            </style>
        <?php endif;
    }

    /**
     * Hides product price from single product page
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    public function hide_product_price_single() {

        $priority = has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' );

        if( $this->check_product_price_single( $priority ) ) {

            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', $priority );

        }

    }

    /**
     * Checks if product price needs to be hidden
     *
     * @param   $priority
     * @param   $product_id
     * @since   1.0.2
     * @author  Alberto Ruggiero
     * @return  bool
     */
    public function check_product_price_single ( $priority = true, $product_id = false ) {

        $hide = false;

        if ( get_option( 'ywctm_enable_plugin' ) == 'yes' && $this->check_user_admin_enable() && get_option( 'ywctm_hide_price' ) == 'yes' ) {

            $ywctm_hide_price_users = ( get_option( 'ywctm_hide_price_users' ) != 'unregistered' ) ? true : false;
            $user_logged = is_user_logged_in();

            if ( ! ( ! $ywctm_hide_price_users && $user_logged )  ) {

                global $post;

                $post_id = ( $product_id ) ? $product_id : $post->ID;

                $exclude_catalog    = get_post_meta( $post_id, '_ywctm_exclude_hide_price', true );
                $enable_exclusion   = get_option( 'ywctm_exclude_hide_price' );
                $alternative_text   = get_option( 'ywctm_exclude_price_alternative_text' );

                if ( $priority ) {

                    if ( ( $enable_exclusion == '' || $enable_exclusion == 'no' ) ) {

                        $hide = true;

                    }
                    else {

                        if ( ( $exclude_catalog == '' || $exclude_catalog == 'no' ) ) {

                            $hide = true;

                        }
                    }
                }
            }

            $reverse_criteria = get_option( 'ywctm_exclude_hide_price_reverse' );

            if ( $reverse_criteria == 'yes' ) {

                $hide = ! $hide;

            }

        }

        if( $hide && $alternative_text != '' )  $hide = false;

        return $hide;

    }

    /**
     * Check for which users will not see the price
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  string
     */
    public function show_product_price( $price ) {

        if ( get_option( 'ywctm_hide_price' ) == 'yes' ) {

            $ywctm_hide_price_users = ( get_option( 'ywctm_hide_price_users' ) != 'unregistered' ) ? true : false;
            $user_logged = is_user_logged_in();

            if ( ! ( ! $ywctm_hide_price_users && $user_logged )  ) {

                $price = $this->set_price_label( $price );

            }

        }

        return $price;

    }

    /**
     * Hides price, if not excluded, and shows alternative text if set
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  string
     */
    private function set_price_label ( $price ) {
        global $post;

        $remove             = false;
        $exclude_catalog    = get_post_meta( $post->ID, '_ywctm_exclude_hide_price', true );
        $enable_exclusion   = get_option( 'ywctm_exclude_hide_price' );
        $alternative_text   = get_option( 'ywctm_exclude_price_alternative_text' );

        if ( $enable_exclusion == '' || $enable_exclusion == 'no' ) {

            $remove = true ;

        } else {

            if ( $exclude_catalog == '' || $exclude_catalog == 'no' ) {

                $remove = true ;

            } else {

                $remove = false ;

            }

        }

        $reverse_criteria = get_option( 'ywctm_exclude_hide_price_reverse' );

        if ( $reverse_criteria == 'yes' ) {

            $remove = ! $remove;

        }

        if ( $remove ) {

            return ( $alternative_text != '' ) ? $alternative_text : '' ;

        } else {

            return $price;

        }

    }

    /**
     * Register plugins for activation tab
     *
     * @return void
     * @since    2.0.0
     * @author   Andrea Grillo <andrea.grillo@yithemes.com>
     */
    public function register_plugin_for_activation() {
        if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
            require_once 'plugin-fw/licence/lib/yit-licence.php';
            require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
        }
        YIT_Plugin_Licence()->register( YWCTM_INIT, YWCTM_SECRET_KEY, YWCTM_SLUG );
    }

    /**
     * Register plugins for update tab
     *
     * @return void
     * @since    2.0.0
     * @author   Andrea Grillo <andrea.grillo@yithemes.com>
     */
    public function register_plugin_for_updates() {
        if ( ! class_exists( 'YIT_Upgrade' ) ) {
            require_once( 'plugin-fw/lib/yit-upgrade.php' );
        }
        YIT_Upgrade()->register( YWCTM_SLUG, YWCTM_INIT );
    }

}