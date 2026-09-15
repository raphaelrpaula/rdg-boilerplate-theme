<?php
function social_customizer($wp_customize)
{
  // Settings
  $wp_customize->add_setting('custom_instagram', array('default' => ''));
  $wp_customize->add_setting('custom_facebook', array('default' => ''));
  $wp_customize->add_setting('custom_linkedin', array('default' => ''));
  $wp_customize->add_setting('custom_x', array('default' => ''));

  // Section
  $wp_customize->add_section('social_section', array(
    'title' => 'Redes Sociais',
    'priority' => 2,
    'panel' => 'theme_options'
  ));

  // Controllers
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_instagram',
      array(
        'label' => 'Link do Instagram',
        'description' => 'Link completo',
        'section' => 'social_section',
        'settings' => 'custom_instagram',
        'type' => 'text',
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_facebook',
      array(
        'label' => 'Link do Facebook',
        'description' => 'Link completo',
        'section' => 'social_section',
        'settings' => 'custom_facebook',
        'type' => 'text',
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_linkedin',
      array(
        'label' => 'Link do Linkedin',
        'description' => 'Link completo',
        'section' => 'social_section',
        'settings' => 'custom_linkedin',
        'type' => 'text',
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_x',
      array(
        'label' => 'Link do X',
        'description' => 'Link completo',
        'section' => 'social_section',
        'settings' => 'custom_x',
        'type' => 'text',
      )
    )
  );
}