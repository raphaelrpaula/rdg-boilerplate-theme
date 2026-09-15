<?php

/**
 * RDG Starter Theme
 * @package RDG
 */

namespace RDG;

define('RDG_THEME_VERSION', '1.0.0');
define('RDG_THEME_PATH', get_template_directory());
define('RDG_THEME_URL', get_template_directory_uri());
define('IMG_PATH_URL', get_template_directory_uri() . '/assets/img');

if (file_exists(get_template_directory() . '/vendor/autoload.php')) {
    require_once get_template_directory() . '/vendor/autoload.php';
}

if (class_exists('RDG\\Core\\Setup')) {
    new Core\Setup();

    if (class_exists('RDG\\Integrations\\ContactForm7')) {
        new Integrations\ContactForm7();
    }
}
