<?php

class CS_Block_Grid_Item extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'block-grid-item',
      'title'       => __( 'Block Grid Item', csl18n() ),
      'section'     => '_content',
      'description' => __( 'Block Grid Item description.', csl18n() ),
      'supports'    => array( 'id', 'class', 'style' ),
      'render'      => false,
      'delegate'    => true
    );
  }

  public function controls() {

    $this->addControl(
      'title',
      'title',
      NULL,
      NULL,
      ''
    );

    $this->addControl(
      'content',
      'editor',
      __( 'Content', csl18n() ),
      __( 'Include your desired content for your Block Grid Item here.', csl18n() ),
      __( 'Add some content to your block grid item here. The block grid will maintain its shape and is intended for images, but you can place anything inside that you want.', csl18n() )
    );

  }

  // public function render( $atts ) {

  //   extract( $atts );

  //   $extra = $this->extra( array(
  //     'id'    => $id,
  //     'class' => $class,
  //     'style' => $style
  //   ) );

  //   $shortcode = "[x_block_grid_item{$extra}][/x_block_grid_item]";

  //   return $shortcode;

  // }

}