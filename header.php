<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#BF0D0D" />
  <meta name="description" content="<?= get_bloginfo('description') ?>">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <header class="header">
    <div class="header-wrapper container" data-aos="fade-down">
      <div class="header--logo logo">
        <?php if (has_custom_logo()) {
          the_custom_logo();
        } else { ?>
        <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>
        <?php } ?>
      </div>

      <div class="header--menu">
        <nav class="main-menu">
          <?php if (has_nav_menu('main-menu')) {
          wp_nav_menu(array(
            'theme_location' => 'main-menu',
            'container' => 'ul',
            'menu_class' => 'menu-list',
            'menu_id' => 'menu',
            'fallback_cb' => false
          ));
        }
        ?>
        </nav>
      </div>
    </div>

    <div class="header-wrapper header-mobile container">
      <div class="header--logo" data-aos="fade-down">
        <?php if (has_custom_logo()) {
          the_custom_logo();
        } else { ?>
        <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>
        <?php } ?>
      </div>

      <div class="header--menu">
        <button id="btn-menu" aria-label="Abrir menu" aria-haspopup="true" aria-controls="menu" aria-expanded="false"
          data-aos="fade-down">
          <img src="<?= IMG_PATH_URL . '/icons/menu-burger.svg'; ?>" alt="" loading="lazy">
        </button>

        <div class="mobile-menu">
          <nav class="main-menu">
            <div class="menu-top-header">
              <img src="<?= IMG_PATH_URL . '/icons/cross.svg'; ?>" alt="" id="close-menu" loading="lazy">
            </div>

            <?php if (has_nav_menu('footer-menu')) {
            wp_nav_menu(array(
              'theme_location' => 'footer-menu',
              'container' => 'ul',
              'menu_class' => 'menu-list',
              'menu_id' => 'menu-mobile',
              'fallback_cb' => false
            ));
              }
            ?>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <main class="main"></main>