<?php
function contato_customizer($wp_customize)
{
  // Settings
  $wp_customize->add_setting('custom_telefone', array('default' => ''));
  $wp_customize->add_setting('custom_whatsapp', array('default' => ''));
  $wp_customize->add_setting('custom_email', array('default' => ''));
  $wp_customize->add_setting('custom_endereco', array('default' => ''));

  // Section
  $wp_customize->add_section('contato_section', array(
    'title' => 'Informações da Empresa',
    'priority' => 1,
    'panel' => 'theme_options'
  ));

  // Controllers
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_telefone',
      array(
        'label' => 'Telefone',
        'description' => 'Telefone com DDD',
        'section' => 'contato_section',
        'settings' => 'custom_telefone',
        'type' => 'text'
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_whatsapp',
      array(
        'label' => 'Whatsapp',
        'description' => 'Whatsapp com DDD',
        'section' => 'contato_section',
        'settings' => 'custom_whatsapp',
        'type' => 'text'
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_email',
      array(
        'label' => 'E-mail',
        'section' => 'contato_section',
        'settings' => 'custom_email',
        'type' => 'text'
      )
    )
  );
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'custom_endereco',
      array(
        'label' => 'Endereço',
        'section' => 'contato_section',
        'settings' => 'custom_endereco',
        'type' => 'textarea'
      )
    )
  );
}