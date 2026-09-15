<?php
function custom_code_customizer($wp_customize)
{
  // Settings
  $wp_customize->add_setting('custom_header', array('default' => ''));
  $wp_customize->add_setting('custom_body', array('default' => ''));
  $wp_customize->add_setting('custom_footer', array('default' => ''));

  // Section
  $wp_customize->add_section('custom_code_section', array(
    'title' => 'Códigos externos',
    'priority' => 3,
    'panel' => 'theme_options'
  ));

  // Controllers
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_header',
      array(
        'label' => 'Adicionar código na tag <head>',
        'section' => 'custom_code_section',
        'settings' => 'custom_header',
        'type' => 'textarea'
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_body',
      array(
        'label' => 'Adicionar código na tag <body>',
        'section' => 'custom_code_section',
        'settings' => 'custom_body',
        'type' => 'textarea'
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_footer',
      array(
        'label' => 'Adicionar código na tag <footer>',
        'section' => 'custom_code_section',
        'settings' => 'custom_footer',
        'type' => 'textarea'
      )
    )
  );
}