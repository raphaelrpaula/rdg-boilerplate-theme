<?php
/**
 * RDG Starter Theme
 * @package RDG
 */

namespace RDG;

// 1. Definição de Constantes (Substituídas pelo setup.php depois)
define( 'RDG_THEME_VERSION', '1.0.0' );
define( 'RDG_THEME_PATH', get_template_directory() );
define( 'RDG_THEME_URL', get_template_directory_uri() );
define( 'IMG_PATH_URL', get_template_directory_uri() . '/assets/img' );

// 2. Autoloader Simples (Padrão PSR-4 adaptado)
spl_autoload_register( function ( $class ) {
    $prefix = 'RDG\\';
    $base_dir = RDG_THEME_PATH . '/inc/';

    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) return;

    $relative_class = substr( $class, $len );
    $file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

    if ( file_exists( $file ) ) {
        require $file;
    }
});

// 3. Inicialização do Tema
if ( class_exists( 'RDG\\Core\\Setup' ) ) {
    new Core\Setup();
}