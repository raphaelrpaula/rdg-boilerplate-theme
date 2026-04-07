<?php
namespace RDG\Core;

class Setup {
  public function __construct() {
    add_action('after_setup_theme', [$this, 'theme_support']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
  }

  public function theme_support() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    register_nav_menus([
      'main-menu' => 'Menu Principal',
      'footer' => 'Menu do Rodapé',
    ]);
  }

  public function enqueue_assets() {
    wp_enqueue_style('swiper-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_style('lenis-style', 'https://unpkg.com/lenis@1.1.14/dist/lenis.css');
    wp_enqueue_style('aos-style', get_template_directory_uri() . '/assets/css/libs/aos.min.css', array(), '');
    wp_enqueue_style('rdg-main', RDG_THEME_URL . '/assets/css/style.min.css', [], RDG_THEME_VERSION);
  
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '', true);
    wp_enqueue_script('lenis-js', 'https://unpkg.com/lenis@1.1.14/dist/lenis.min.js', array(), '', true);
    wp_enqueue_script('gsap-js', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '', true);
    wp_enqueue_script('scroll-trigger-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array(), '', true);
    wp_enqueue_script('scroll-smoother-js', 'https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/ScrollSmoother.min.js', array(), '', true);
    wp_enqueue_script('aos-js', get_template_directory_uri() . '/assets/js/libs/aos.min.js', array(), '', true);
    wp_enqueue_script('rdg-js', RDG_THEME_URL . '/assets/js/main.min.js', [], RDG_THEME_VERSION, true);
  }

  public function footer_widgets() {
    register_sidebar(array(
      'name' => __('footer Widgets', 'rdg-slug'),
      'id' => 'footer-widgets',
      'description' => __('Widgets que aparecem no Rodapé', 'rdg-slug'),
      'before_title' => '<h4>',
      'after_title' => '</h4>',
      'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
      'after_widget' => '</div>'
    ));
  }
}