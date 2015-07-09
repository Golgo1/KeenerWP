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
    exit;
} // Exit if accessed directly

/**
 * Displays the exclusions table in YWCTM plugin admin tab
 *
 * @class   YWCTM_Blocklist_Table
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWCTM_Exclusions_Table {

    /**
     * Outputs the exclusions table template with insert form in plugin options panel
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  string
     */
    public static function output() {

        global $wpdb;

        $table = new YITH_Custom_Table( array(
            'singular'  => __( 'product', 'ywctm' ),
            'plural'    => __( 'products', 'ywctm' )
        ) );

        $table->options = array(
            'select_table'      => $wpdb->prefix . 'posts a INNER JOIN ' . $wpdb->prefix . 'postmeta b ON a.ID = b.post_id',
            'select_columns'    => array(
                'a.ID',
                'a.post_title',
                'MAX(CASE WHEN b.meta_key = "_ywctm_exclude_catalog_mode" THEN b.meta_value ELSE NULL END) AS add_to_cart',
                'MAX(CASE WHEN b.meta_key = "_ywctm_exclude_hide_price" THEN b.meta_value ELSE NULL END) AS price'
            ),
            'select_where'      => 'a.post_type = "product" AND ( b.meta_key = "_ywctm_exclude_catalog_mode" OR b.meta_key = "_ywctm_exclude_hide_price" ) AND b.meta_value = "yes"',
            'select_group'      => 'a.ID',
            'select_order'      => 'a.post_title',
            'select_order_dir'  => 'ASC',
            'per_page_option'   => 'items_per_page',
            'count_table'       => '( SELECT COUNT(*) FROM ' . $wpdb->prefix . 'posts a INNER JOIN ' . $wpdb->prefix . 'postmeta b ON a.ID = b.post_id  WHERE a.post_type = "product" AND (b.meta_key = "_ywctm_exclude_catalog_mode" OR b.meta_key = "_ywctm_exclude_hide_price") AND b.meta_value="yes" GROUP BY a.ID ) AS count_table',
            'count_where'       => '',
            'key_column'        => 'ID',
            'view_columns'      => array(
                'cb'            => '<input type="checkbox" />',
                'product'       => __( 'Product', 'ywctm' ),
                'add_to_cart'   => __( 'Show "Add to cart"', 'ywctm' ),
                'price'         => __( 'Show price', 'ywctm' )
            ),
            'hidden_columns'    => array(),
            'sortable_columns'  => array(
                'product'    => array( 'post_title', true )
            ),
            'custom_columns'    => array(
                'column_product'        => function ( $item, $me ) {

                    $edit_query_args    = array(
                        'page'      => $_GET['page'],
                        'tab'       => $_GET['tab'],
                        'action'    => 'edit',
                        'id'        => $item['ID']
                    );
                    $edit_url           = esc_url( add_query_arg( $edit_query_args, admin_url( 'admin.php' ) ) );

                    $delete_query_args  = array(
                        'page'      => $_GET['page'],
                        'tab'       => $_GET['tab'],
                        'action'    => 'delete',
                        'id'        => $item['ID']
                    );
                    $delete_url         = esc_url( add_query_arg( $delete_query_args, admin_url( 'admin.php' ) ) );

                    $product_query_args = array(
                        'post'      => $item['ID'],
                        'action'    => 'edit'
                    );
                    $product_url        = esc_url( add_query_arg( $product_query_args, admin_url( 'post.php' ) ) );

                    $actions            = array(
                        'edit'      => '<a href="' . $edit_url . '">' . __( 'Edit exclusions', 'ywctm' ) . '</a>',
                        'delete'    => '<a href="' . $delete_url . '">' . __( 'Remove from exclusions', 'ywctm' ) . '</a>',
                    );

                    return sprintf( '<strong><a class="tips" target="_blank" href="%s" data-tip="%s">#%d %s </a></strong> %s', $product_url, __( 'Edit product','ywctm' ), $item['ID'], $item['post_title'], $me->row_actions( $actions ) );
                },
                'column_add_to_cart'    => function ( $item, $me ) {

                     if ( $item['add_to_cart'] == 'yes' ){
                         $class = 'show';
                         $tip   = __( 'Yes', 'ywctm');
                     } else {
                         $class = 'hide';
                         $tip   = __( 'No', 'ywctm');
                     }

                     return sprintf( '<mark class="%s tips" data-tip="%s">%s</mark>', $class, $tip, $tip );

                 },
                'column_price'          => function ( $item, $me ) {

                     if ( $item['price'] == 'yes' ){
                         $class = 'show';
                         $tip   = __( 'Yes', 'ywctm');
                     } else {
                         $class = 'hide';
                         $tip   = __( 'No', 'ywctm');
                     }

                     return sprintf( '<mark class="%s tips" data-tip="%s">%s</mark>', $class, $tip, $tip );

                 }
            ),
            'bulk_actions'      => array(
                'actions'   => array(
                    'delete'    => __( 'Remove from list','ywctm' )
                ),
                'functions' => array(
                    'function_delete'    => function () {
                        global $wpdb;

                        $ids = isset( $_GET['id'] ) ? $_GET['id'] : array();
                        if ( is_array( $ids ) ) $ids = implode( ',', $ids );

                        if ( !empty( $ids ) ) {
                            $wpdb->query( "UPDATE {$wpdb->prefix}postmeta
                                           SET meta_value='no'
                                           WHERE ( meta_key = '_ywctm_exclude_catalog_mode' OR meta_key = '_ywctm_exclude_hide_price' ) AND post_id IN ( $ids )"
                            );
                        }
                    }
                )
            ),
        );

        $table->prepare_items();

        $message    = '';
        $notice     = '';

        $default = array(
            'ID'            => 0,
            'post_title'    => '',
            'add_to_cart'   => '',
            'price'         => ''
        );

        $list_query_args = array(
            'page'  => $_GET['page'],
            'tab'   => $_GET['tab']
        );

        $list_url = esc_url( add_query_arg( $list_query_args, admin_url( 'admin.php' ) ) );

        if ( 'delete' === $table->current_action() ) {
            $message = sprintf( _n( '%s product removed successfully', '%s products removed successfully', count( $_GET['id'] ), 'ywctm' ), count( $_GET['id'] ) );
        }

        if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], basename( __FILE__ ) ) ) {

            $item_valid = self::validate_fields( $_POST );

            if ( $item_valid !== true ){

                $notice = $item_valid;

            } else {

                $show_cart  = isset( $_POST['show-cart'] ) ? 'yes' : 'no';
                $show_price = isset( $_POST['show-price'] ) ? 'yes' : 'no';

                foreach( $_POST['products'] as $product_id ){
                    update_post_meta( $product_id, '_ywctm_exclude_catalog_mode', $show_cart );
                    update_post_meta( $product_id, '_ywctm_exclude_hide_price', $show_price );
                }

                if ( ! empty( $_POST['insert'] ) ) {

                    $message = sprintf( _n( '%s product added successfully', '%s products added successfully', count( $_POST['products'] ), 'ywctm' ), count( $_POST['products'] ) );

                } elseif ( ! empty( $_POST['update'] ) ) {

                    $message = __( 'Product updated successfully' , 'ywctm' );

                }

            }

        }

        $item = $default;

        if ( isset( $_GET['id'] ) ) {

            $select_table   = $table->options['select_table'];
            $select_columns = implode( ',', $table->options['select_columns'] );
            $item           = $wpdb->get_row( $wpdb->prepare( "SELECT $select_columns FROM $select_table WHERE a.id = %d", $_GET['id'] ), ARRAY_A );

        }

        ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br/></div>
            <h2><?php _e('Exclusion list', 'ywctm');

                if ( empty( $_GET[ 'action' ] ) || ( 'insert' !== $_GET[ 'action' ] && 'edit' !== $_GET[ 'action' ] ) ) : ?>
                    <?php $query_args = array(
                        'page'      => $_GET['page'],
                        'tab'       => $_GET['tab'],
                        'action'    => 'insert'
                    );
                    $add_form_url = esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
                    ?>
                   <a class="add-new-h2" href="<?php echo $add_form_url; ?>"><?php _e( 'Add Products', 'ywctm' ); ?></a>
                <?php endif; ?>
            </h2>
        <?php if ( ! empty( $notice ) ) : ?>
            <div id="notice" class="error below-h2"><p><?php echo $notice; ?></p></div>
        <?php endif;

        if ( ! empty( $message ) ) : ?>
            <div id="message" class="updated below-h2"><p><?php echo $message; ?></p></div>
        <?php endif;

        if ( ! empty( $_GET['action'] ) && ( 'insert' == $_GET['action'] ||  'edit' == $_GET['action'] ) ) : ?>

            <form id="form" method="POST">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>"/>
                <table class="form-table">
                    <tbody>
                        <tr valign="top" class="titledesc">
                            <th scope="row">
                                <label for="product"><?php _e( 'Products to exclude', 'ywctm' ); ?></label>
                            </th>
                            <td class="forminp yith-choosen">

                                <?php if ( 'insert' == $_GET['action'] ) :?>

                                    <select id="product" name="products[]" class="ajax_chosen_select_product" style="width: 50%" multiple="multiple" placeholder="Search for product"></select>

                                <?php else :?>

                                    <input id="product" name="products[]" type="hidden" value="<?php echo esc_attr( $item['ID'] ); ?>"/>
                                    <?php printf( '<b>#%d %s</b>', esc_attr( $item['ID'] ), esc_attr( $item['post_title'] ) ); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr valign="top" class="titledesc">
                            <th scope="row">
                                <label for="show-cart"><?php _e( 'Show "Add to cart" button', 'ywctm' ); ?></label>
                            </th>
                            <td class="forminp forminp-email">
                                <input id="show-cart" name="show-cart" type="checkbox" <?php echo ( esc_attr( $item['add_to_cart'] ) == 'yes' ) ? 'checked="checked"' : ''; ?>/>
                            </td>
                        </tr>
                        <tr valign="top" class="titledesc">
                            <th scope="row">
                                <label for="show-price"><?php _e( 'Show product price', 'ywctm' ); ?></label>
                            </th>
                            <td class="forminp forminp-checkbox">
                                <input id="show-price" name="show-price" type="checkbox" <?php echo ( esc_attr( $item['price'] ) == 'yes' ) ? 'checked="checked"' : ''; ?> />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php if ( 'insert' == $_GET['action'] ) :?>

                    <input type="submit" value="<?php _e( 'Add product exclusion', 'ywctm' ); ?>" id="insert" class="button-primary" name="insert">

                <?php else :?>

                    <input type="submit" value="<?php _e( 'Update product exclusion', 'ywctm' ); ?>" id="update" class="button-primary" name="update">

                <?php endif; ?>
                <a class="button-secondary" href="<?php echo $list_url; ?>"><?php _e( 'Return to exclusion list', 'ywctm' ); ?></a>
            </form>
        <?php else : ?>
            <form id="custom-table" method="GET" action="<?php echo $list_url; ?>">
                <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>"/>
                <input type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>"/>
                <?php $table->display(); ?>
            </form>
        <?php endif; ?>
        </div>
        <?php

        wp_enqueue_script('ajax-chosen');

        $inline_js = "
                jQuery( 'select.ajax_chosen_select_product' ).ajaxChosen({
                    method:         'GET',
                    url:            '" . admin_url( 'admin-ajax.php' ) . "',
                    dataType:       'json',
                    afterTypeDelay: 100,
                    minTermLength:  1,
                    data:  {
                        action:     'woocommerce_json_search_products',
                        security:   '" . wp_create_nonce( "search-products" ) . "',
                        default:    ''
                    }
                }, function ( data ) {

                    var terms = {};

                    $.each( data, function ( i, val ) {
                        terms[i] = val;
                    });

                    return terms;
                });
            ";

        wc_enqueue_js( $inline_js );
    }

    /**
     * Validate input fields
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @param   $item array POST data array
     * @return  bool|string
     */
    static function validate_fields( $item ) {
        $messages = array();

        if ( empty( $item['products'] ) ) $messages[] = __( 'Select at least one product', 'ywctm' );
        if ( empty( $item['show-cart'] ) && empty( $item['show-price'] ) ) $messages[] = __( 'Select at least one option', 'ywctm' );

        if ( empty( $messages ) ) return true;
        return implode( '<br />', $messages );
    }

    /**
     * Add screen options for exclusions list table template
     *
     * @since   1.0.6
     * @return  void
     * @author  Alberto Ruggiero
     */
    public static function add_options() {
        if ( 'yit-plugins_page_yith_wc_catalog_mode_panel' == get_current_screen()->id && ( isset( $_GET['tab'] ) && $_GET['tab'] == 'exclusions' ) && ( ! isset( $_GET['action'] ) || ( $_GET['action'] != 'edit' && $_GET['action'] != 'insert' ) ) ) {

            $option = 'per_page';

            $args = array(
                'label'     => __( 'Products', 'ywrr' ),
                'default'   => 10,
                'option'    => 'items_per_page'
            );

            add_screen_option( $option, $args );

        }
    }

    /**
     * Set screen options for exclusions list table template
     *
     * @since   1.0.6
     * @param   $status
     * @param   $option
     * @param   $value
     * @return  mixed
     * @author  Alberto Ruggiero
     */
    public static function set_options( $status, $option, $value ) {

        return ( 'items_per_page' == $option ) ? $value : $status;

    }

}