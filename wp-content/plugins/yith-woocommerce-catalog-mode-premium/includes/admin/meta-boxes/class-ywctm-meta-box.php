<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Shows Meta Box in order's details page
 *
 * @class   YWCTM_Meta_Box
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWCTM_Meta_Box {

    /**
     * Output Meta Box
     *
     * The function to be called to output the meta box in product details page.
     *
     * @param   $post object the current product
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    public static function output( $post ) {

        wp_nonce_field( 'woocommerce_save_data', 'woocommerce_meta_nonce' );

        $args = array(
            'add_to_cart' => array(
                'id'            => '_ywctm_exclude_catalog_mode',
                'wrapper_class' => '',
                'label'         => __( '"Add to cart" button', 'ywctm' ),
                'description'   => 'Exclude this product from hiding "Add to cart" button'
            ),
            'price' => array(
                'id'            => '_ywctm_exclude_hide_price',
                'wrapper_class' => '',
                'label'         => __( 'Product price', 'ywctm' ),
                'description'   => 'Exclude this product from hiding price'
            )
        );
        ?>
        <div class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php  woocommerce_wp_checkbox( $args['add_to_cart'] ); ?>
                <?php  woocommerce_wp_checkbox( $args['price'] ); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Save Meta Box
     *
     * The function to be called to save the meta box options.
     *
     * @param   $post_id object the current product id
     * @param   $post object the current product
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  void
     */
    public static function save( $post_id, $post ) {
        global $wpdb;

        $product_type    = empty( $_POST['product-type'] ) ? 'simple' : sanitize_title( stripslashes( $_POST['product-type'] ) );
        $catalog_mode = isset( $_POST['_ywctm_exclude_catalog_mode'] ) ? 'yes' : 'no';
        $hide_price = isset( $_POST['_ywctm_exclude_hide_price'] ) ? 'yes' : 'no';

        update_post_meta( $post_id, '_ywctm_exclude_catalog_mode', $catalog_mode );
        update_post_meta( $post_id, '_ywctm_exclude_hide_price', $hide_price );

        do_action( 'woocommerce_process_product_meta_' . $product_type, $post_id );

        // Clear cache/transients
        wc_delete_product_transients( $post_id );
    }

}