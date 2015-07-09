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



$no_form_plugin     = '';
$inquiry_form       = '';
$yit_contact_form   = '';
$contact_form_7     = '';
$active_plugins     = array(
    'none' => __( 'None', 'ywctm' ),
);

if ( function_exists( 'YIT_Contact_Form' ) ) {
    $active_plugins['yit-contact-form'] = __( 'YIT Contact Form', 'ywctm' );
}

if ( function_exists( 'wpcf7_contact_form' ) ) {
    $active_plugins['contact-form-7'] = __( 'Contact Form 7', 'ywctm' );
}

if ( function_exists( 'YIT_Contact_Form' ) || function_exists( 'wpcf7_contact_form' ) ) {

    $inquiry_form       = array(
        'name'              => __( 'Inquiry form', 'ywctm' ),
        'type'              => 'select',
        'desc'              => __( 'Choose one among activated plugins to display an inquiry form in product page', 'ywctm' ),
        'options'           => $active_plugins,
        'default'           => 'none',
        'id'                => 'ywctm_inquiry_form_type'
    );

    $yit_contact_form   = array(
        'name'              => '',
        'type'              => 'select',
        'desc'              => __( 'Choose the form to display', 'ywctm' ),
        'options'           => apply_filters( 'yit_get_contact_forms', array() ),
        'id'                => 'ywctm_inquiry_yit_contact_form_id',
        'class'             => 'yit-contact-form'
    );

    $contact_form_7     = array(
        'name'              => '',
        'type'              => 'select',
        'desc'              => __( 'Choose the form to display', 'ywctm' ),
        'options'           => apply_filters( 'wpcf7_get_contact_forms', array() ),
        'id'                => 'ywctm_inquiry_contact_form_7_id',
        'class'             => 'contact-form-7'
    );

} else {

    $no_form_plugin     =  __( 'To use this feature, YIT Contact Form or Contact Form 7 must be installed and activated.', 'ywctm' );

}

return array(
    'premium' => array(
        'catalog_mode_premium_section_title_price'          => array(
            'name'              => __( 'Product Price', 'ywctm' ),
            'type'              => 'title',
            'desc'              => '',
            'id'                => 'ywctm_premium_title_price',
        ),
        'catalog_mode_premium_hide_price'                   => array(
            'name'              => __( 'Product Price', 'ywctm' ),
            'type'              => 'checkbox',
            'desc'              => __( 'Hide', 'ywctm' ),
            'id'                => 'ywctm_hide_price',
            'default'           => 'no',
            'checkboxgroup'     => 'start'
        ),
        'catalog_mode_premium_exclude_products'             => array(
            'name'              => '',
            'type'              => 'checkbox',
            'desc'              => __( 'Exclude selected products (See "Exclusions" tab)', 'ywctm' ),
            'id'                => 'ywctm_exclude_hide_price',
            'default'           => 'no',
            'checkboxgroup'     => ''
        ),
        'catalog_mode_premium_exclude_products_reverse'     => array(
            'name'              => '',
            'type'              => 'checkbox',
            'desc'              => __( 'Reverse Exclusion List (Restrict Catalog Mode to selected items only)', 'ywctm' ),
            'id'                => 'ywctm_exclude_hide_price_reverse',
            'default'           => 'no',
            'checkboxgroup'     => 'end'
        ),
        'catalog_mode_premium_price_alternative_text'       => array(
            'name'              => __( 'Alternative text', 'ywctm' ),
            'type'              => 'text',
            'desc'              => __( 'This text will replace price (Optional)', 'ywctm' ),
            'id'                => 'ywctm_exclude_price_alternative_text',
            'default'           => '',
        ),
        'catalog_mode_premium_hide_price_users'             => array(
            'name'              => __( 'Target users', 'ywctm' ),
            'type'              => 'select',
            'desc'              => __( 'Users who will not see the price and the add to cart button', 'ywctm' ),
            'options'           => array(
                'all'           => __('All users', 'ywctm'),
                'unregistered'  => __('Unregistered users only', 'ywctm')
            ),
            'default'           => 'all',
            'id'                => 'ywctm_hide_price_users'
        ),
        'catalog_mode_premium_section_end_price'            => array(
            'type'              => 'sectionend',
            'id'                => 'ywctm_premium_end_price'
        ),
        'catalog_mode_premium_section_title_form'           => array(
            'name'              => __( 'Inquiry Form', 'ywctm' ),
            'type'              => 'title',
            'desc'              => $no_form_plugin,
            'id'                => 'ywctm_premium_title_form',
        ),
        'catalog_mode_premium_inquiry_form'                 => $inquiry_form,
        'catalog_mode_premium_yit_contact_form'             => $yit_contact_form,
        'catalog_mode_premium_contact_form_7'               => $contact_form_7,
        'catalog_mode_premium_section_end_form'             => array(
            'type'              => 'sectionend',
            'id'                => 'ywctm_premium_end_form'
        ),
        'catalog_mode_premium_section_title_button'         => array(
            'name'              => __( 'Custom Button', 'ywctm' ),
            'type'              => 'title',
            'desc'              => '',
            'id'                => 'ywctm_premium_title_button',
        ),
        'catalog_mode_premium_enable_custom_button'         => array(
            'name'              => __( 'Custom button', 'ywctm' ),
            'type'              => 'checkbox',
            'desc'              => __( 'Show in product details page', 'ywctm' ),
            'id'                => 'ywctm_custom_button',
            'default'           => 'no',
            'checkboxgroup'     => 'start'
        ),
        'catalog_mode_premium_enable_custom_button_loop'    => array(
            'name'              => __( 'Custom button', 'ywctm' ),
            'type'              => 'checkbox',
            'desc'              => __( 'Show in shop page', 'ywctm' ),
            'id'                => 'ywctm_custom_button_loop',
            'default'           => 'no',
            'checkboxgroup'     => 'end'
        ),
        'catalog_mode_premium_custom_button_text'           => array(
            'name'              => __( 'Button text','ywctm' ),
            'type'              => 'text',
            'desc'              => '',
            'id'                => 'ywctm_button_text',
        ),
        'catalog_mode_premium_custom_button_color'          => array(
            'name'              => __( 'Color', 'ywctm' ),
            'type'              => 'color',
            'desc'              => __( 'Color of the text (Optional)', 'ywctm' ),
            'id'                => 'ywctm_button_color',
        ),
        'catalog_mode_premium_custom_button_hover'          => array(
            'name'              => __( 'Hover Color', 'ywctm' ),
            'type'              => 'color',
            'desc'              => __( 'Color of the text on hover (Optional)', 'ywctm' ),
            'id'                => 'ywctm_button_hover',
        ),
        'catalog_mode_premium_custom_button_icon'           => array(
            'name'              => __( 'Icon', 'ywctm' ),
            'type'              => 'icon',
            'desc'              => __( 'Show optional icon', 'ywctm' ),
            'options'           => array(
                'select'    => array(
                    'none'   => __( 'None', 'ywctm' ),
                    'icon'   => __( 'Theme Icon', 'ywctm' ),
                    'custom' => __( 'Custom Icon', 'ywctm' )
                ),
                'icon'      => YIT_Plugin_Common::get_icon_list(),
            ),
            'id'                => 'ywctm_button_icon',
            'default'           => array(
                'select'    => 'none',
                'icon'      => 'retinaicon-font:retina-the-essentials-082',
                'custom'    => ''
            )
        ),
        'catalog_mode_premium_custom_button_url_type'       => array(
            'name'              => __( 'URL Protocol Type', 'ywctm' ),
            'type'              => 'select',
            'desc'              => __( 'Specify the type of the URL (Optional)', 'ywctm' ),
            'options'           => array(
                'generic'   => __( 'Generic URL', 'ywctm'),
                'mailto'    => __( 'E-mail address', 'ywctm'),
                'tel'       => __( 'Phone number', 'ywctm'),
                'skype'     => __( 'Skype contact', 'ywctm'),
            ),
            'default'           => 'generic',
            'id'                => 'ywctm_button_url_type'
        ),
        'catalog_mode_premium_custom_button_url'            => array(
            'name'              => __( 'URL Link', 'ywctm' ),
            'type'              => 'text',
            'desc'              => __( 'Specify the URL (Optional)', 'ywctm' ),
            'id'                => 'ywctm_button_url',
            'default'           => '',
        ),
        'catalog_mode_premium_section_end_button'           => array(
            'type'              => 'sectionend',
            'id'                => 'ywctm_premium_end_button'
        ),
        'catalog_mode_premium_section_title_review'         => array(
            'name'              => __( 'Reviews', 'ywctm' ),
            'type'              => 'title',
            'desc'              => '',
            'id'                => 'ywctm_premium_title_review',
        ),
        'catalog_mode_premium_disable_review'               => array(
            'name'              => __( 'Product Reviews', 'ywctm' ),
            'type'              => 'select',
            'desc'              => '',
            'id'                => 'ywctm_disable_review',
            'default'           => 'no',
            'options'           => array(
                'no'            => __( 'Enabled', 'ywctm' ),
                'all'           => __( 'Disabled for all users', 'ywctm' ),
                'unregistered'  => __( 'Disabled only for unregistered users', 'ywctm' )
            )
        ),
        'catalog_mode_premium_section_end_review'           => array(
            'type'              => 'sectionend',
            'id'                => 'ywctm_premium_end_review'
        )
    )

);