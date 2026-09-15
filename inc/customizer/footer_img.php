<?php
function footer_img_customizer($wp_customize)
{
  // Settings
  $wp_customize->add_setting('custom_footer_img', array('default' => ''));

  // Controllers
  $wp_customize->add_control(
    new wp_customize_Image_Control(
      $wp_customize,
      'custom_footer_img',
      array(
        'label' => 'Logo do Rodapé',
        'section' => 'title_tagline',
        'settings' => 'custom_footer_img',
      )
    )
  );
}