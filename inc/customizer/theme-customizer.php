<?php
require get_template_directory() . '/inc/customizer/social.php';
require get_template_directory() . '/inc/customizer/contato.php';
require get_template_directory() . '/inc/customizer/custom_code.php';
require get_template_directory() . '/inc/customizer/footer_img.php';

function customize_register($wp_customize)
{
  $wp_customize->get_section('title_tagline')->title = 'Informações Principais';
  $wp_customize->get_section('custom_css')->description = '';

  $wp_customize->add_panel('theme_options', array(
    'title' => 'Opções do Tema',
    'priority' => 1
  ));

  contato_customizer($wp_customize);
  social_customizer($wp_customize);
  custom_code_customizer($wp_customize);
  footer_img_customizer($wp_customize);
}