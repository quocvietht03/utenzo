<?php
/**
 * Import static func
 *
 * @package Import Pack
 */

if( ! function_exists( 'utenzo_import_pack_scripts' ) ) {
    /**
     * Import pack load scripts
     *
     */
    function utenzo_import_pack_scripts() {

        wp_enqueue_style( 'import-pack-css', get_template_directory_uri() . '/install/import-pack/dist/import-pack.css', false, wp_get_theme()->get( 'Version' ) );
        wp_enqueue_script( 'import-pack-js', get_template_directory_uri() . '/install/import-pack/dist/import-pack.js', ['jquery'], wp_get_theme()->get( 'Version' ), true );

        wp_localize_script( 'import-pack-js', 'import_pack_php_data', apply_filters( 'utenzo/import_pack/localize_script_data', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        ] ) );
    }

    add_action( 'admin_enqueue_scripts', 'utenzo_import_pack_scripts' );
}
