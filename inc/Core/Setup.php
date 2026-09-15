<?php

namespace RDG\Core;

class Setup
{
  public function __construct()
  {
    add_action('admin_init', [$this, 'register_theme_options']);
    add_action('after_setup_theme', [$this, 'theme_support']);
    add_action('after_setup_theme', [$this, 'configure_theme_palette']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    add_action('widgets_init', [$this, 'footer_widgets']);
    add_action('customize_register', 'customize_register');

    add_filter('gutenberg_use_widgets_block_editor', '__return_false', 100);
    add_filter('use_widgets_block_editor', '__return_false');
    add_filter('wpcf7_form_elements', [$this, 'vtc_wpcf7_remove_span_wrapper']);
  }

  public function theme_support()
  {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    register_nav_menus([
      'main-menu' => 'Menu Principal',
      'footer' => 'Menu do Rodapé',
    ]);
  }

  /**
   * Lê o JSON e configura a paleta de cores do editor
   */
  public function configure_theme_palette()
  {
    $config_path = get_template_directory() . '/theme-config.json';

    if (!file_exists($config_path)) return;

    $config = json_decode(file_get_contents($config_path), true);

    if (isset($config['colors'])) {
      $wp_palette = [];

      foreach ($config['colors'] as $slug => $hex) {
        $wp_palette[] = [
          'name'  => ucwords(str_replace('-', ' ', $slug)),
          'slug'  => $slug,
          'color' => $hex,
        ];
      }

      add_theme_support('editor-color-palette', $wp_palette);
    }
  }

  public function enqueue_assets()
  {
    wp_enqueue_style('swiper-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_style('lenis-style', 'https://unpkg.com/lenis@1.1.14/dist/lenis.css');
    wp_enqueue_style('aos-style', get_template_directory_uri() . '/assets/css/libs/aos.min.css', array(), '');
    wp_enqueue_style('rdg-style', RDG_THEME_URL . '/assets/css/style.min.css', [], RDG_THEME_VERSION);

    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '', true);
    wp_enqueue_script('lenis', 'https://unpkg.com/lenis@1.1.14/dist/lenis.min.js', array(), '', true);
    wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '', true);
    wp_enqueue_script('scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array(), '', true);
    wp_enqueue_script('aos', get_template_directory_uri() . '/assets/js/libs/aos.min.js', array(), '', true);
    wp_enqueue_script_module('video-js', 'https://cdn.jsdelivr.net/npm/@videojs/html/cdn/video-minimal.js', array(), '');
    wp_enqueue_script_module('mux-video', 'https://cdn.jsdelivr.net/npm/@videojs/html/cdn/media/mux-video.js', array(), '');
    wp_enqueue_script('rdg', RDG_THEME_URL . '/assets/js/main.min.js', [], RDG_THEME_VERSION, true);
  }

  public function footer_widgets()
  {
    register_sidebar(array(
      'name' => __('footer Widgets', 'rdg'),
      'id' => 'footer-widgets',
      'description' => __('Widgets que aparecem no Rodapé', 'rdg'),
      'before_title' => '<h4>',
      'after_title' => '</h4>',
      'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
      'after_widget' => '</div>'
    ));
  }

  public function register_theme_options()
  {
    register_setting('theme-option-page', 'theme-options');
  }

  public function vtc_wpcf7_remove_span_wrapper($content)
  {
    $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

    return $content;
  }
}