jQuery( document ).ready(function( $ ){
    "use strict";

    //Contact form selection
    var yit_contact_form   = $( 'select.yit-contact-form' ).parent().parent(),
        contact_form_7     = $( 'select.contact-form-7' ).parent().parent();

    $( 'select#ywctm_inquiry_form_type' ).change(function(){

        var option = $( 'option:selected', this ).val();

        switch( option ){
            case "yit-contact-form":
                yit_contact_form.show();
                contact_form_7.hide();
                break;
            case "contact-form-7":
                yit_contact_form.hide();
                contact_form_7.show();
                break;
            default:
                yit_contact_form.hide();
                contact_form_7.hide();
        }

    }).change();

    //Custom button activation

    $( 'input#ywctm_custom_button' ).change(function(){

        if ( $( this ).is( ':checked' ) ){
            $( 'input#ywctm_button_text' ).prop( 'required', true )
        } else {
            $( 'input#ywctm_button_text' ).prop( 'required', false )
        }

    }).change();

    //Custom button icon selection
    var icon_list      = $( '.icon-option.icon-list' ),
        custom_icon    = $( '.icon-option.custom-icon' );

    $( 'select.icon_list_type' ).change(function(){

        var option  = $( 'option:selected', this ).val();

        switch( option ){
            case "icon":
                icon_list.show();
                custom_icon.hide();
                break;
            case "custom":
                icon_list.hide();
                custom_icon.show();
                break;
            default:
                icon_list.hide();
                custom_icon.hide();
        }
    }).change();

    var element_list   = $( 'ul.icon-list-wrapper > li' ),
        icon_preview   = $( '.icon-preview' ),
        icon_text      = $( '.icon-text' );

    element_list.on( 'click', function () {
        var current = $(this);
        element_list.removeClass('active');
        current.addClass('active');
        icon_preview.attr('data-font', current.data('font'));
        icon_preview.attr('data-icon', current.data('icon'));
        icon_preview.attr('data-name', current.data('name'));
        icon_preview.attr('data-key', current.data('key'));

        icon_text.val(current.data('font') + ':' + current.data('name'));

    });

    //upload icon

    var _custom_media           = true,
        _orig_send_attachment   = wp.media.editor.send.attachment,
        upload_button           = $( '.forminp-custom-icon .upload_button' ),
        upload_img_url          = $( '.forminp-custom-icon .upload_img_url' ),
        upload_preview          = $( '.forminp-custom-icon .upload_img_preview img' );

    upload_img_url.change(function(){
        var url = upload_img_url.val(),
            re  = new RegExp( '(http|ftp|https)://[a-zA-Z0-9@?^=%&amp;:/~+#-_.]*.(gif|jpg|jpeg|png|ico)' );

        if( re.test( url ) ) {
            upload_preview.attr( 'src', url );
        } else {
            upload_preview.attr( '' );
        }
    }).change();

    upload_button.on( 'click', function() {

        var send_attachment_bkp = wp.media.editor.send.attachment;
        _custom_media = true;

        wp.media.editor.send.attachment = function( props, attachment ){
            if ( _custom_media ) {
                upload_img_url.val( attachment.url ).change()
            } else {
                return _orig_send_attachment.apply( this, [props, attachment] );
            }
        };

        wp.media.editor.open( upload_button );
        return false;
    });

    $( '.add_media' ).on( 'click', function(){
        _custom_media = false;
    });

});

