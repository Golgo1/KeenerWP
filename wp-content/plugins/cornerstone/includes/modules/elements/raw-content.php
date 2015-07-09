<?php

class CS_Raw_Content extends Cornerstone_Element_Base {

  public function data() {
    return array(
      'name'        => 'raw-content',
      'title'       => __( 'Raw Content', csl18n() ),
      'section'     => 'content',
      'description' => __( 'Raw Content description.', csl18n() ),
      'supports'    => array( 'id', 'class', 'style' ),
      'empty'       => array( 'content' => '' )
    );
  }

  public function controls() {

    $this->addControl(
      'content',
      'textarea',
      __( 'Content', csl18n() ),
      __( 'Accepts shortcodes and no special formatting is applied to this output. Keep in mind if your markup is empty or styled in a way that you cannot see it and you click away, you will not be able to get back to this element.', csl18n() ),
      '',
      array(
        'monospace' => true
      )
    );

  }

  public function render( $atts ) {

    extract( $atts );

    $shortcode = "[x_raw_content{$extra}]{$content}[/x_raw_content]";

    return $shortcode;

  }

}