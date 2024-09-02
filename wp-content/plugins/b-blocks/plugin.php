<?php

/**
 * Plugin Name: B Blocks
 * Description: Gutenberg Blocks Collection and Page Builder.
 * Version: 1.9.3
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: b-blocks
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'b_blocks_fs' ) ) {
    register_activation_hook( __FILE__, function () {
        if ( is_plugin_active( 'b-blocks/plugin.php' ) ) {
            deactivate_plugins( 'b-blocks/plugin.php' );
        }
        if ( is_plugin_active( 'b-blocks-pro/plugin.php' ) ) {
            deactivate_plugins( 'b-blocks-pro/plugin.php' );
        }
    } );
} else {
    // Constant
    define( 'B_BLOCKS_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.9.3' ) );
    define( 'B_BLOCKS_DIR_PATH', plugin_dir_path( __FILE__ ) );
    define( 'B_BLOCKS_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'B_BLOCKS_DIST', B_BLOCKS_DIR_URL . 'dist/' );
    define( 'B_BLOCKS_ASSETS', B_BLOCKS_DIR_URL . 'assets/' );
    if ( !function_exists( 'b_blocks_fs' ) ) {
        // Create a helper function for easy SDK access.
        function b_blocks_fs() {
            global $b_blocks_fs;
            if ( !isset( $b_blocks_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $b_blocks_fs = fs_dynamic_init( array(
                    'id'             => '12845',
                    'slug'           => 'b-blocks',
                    'premium_slug'   => 'b-blocks-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_d6426b344df4918bc066a7a0b4719',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 7,
                        'is_require_payment' => true,
                    ),
                    'menu'           => array(
                        'slug'       => 'b-blocks',
                        'first-path' => 'admin.php?page=b-blocks',
                        'contact'    => false,
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $b_blocks_fs;
        }

        // Init Freemius.
        b_blocks_fs();
        // Signal that SDK was initiated.
        do_action( 'b_blocks_fs_loaded' );
    }
    // Generate Styles
    class BBlocksStyleGenerator {
        public static $styles = [];

        public static function addStyle( $selector, $styles ) {
            if ( array_key_exists( $selector, self::$styles ) ) {
                self::$styles[$selector] = wp_parse_args( self::$styles[$selector], $styles );
            } else {
                self::$styles[$selector] = $styles;
            }
        }

        public static function renderStyle() {
            $output = '';
            foreach ( self::$styles as $selector => $style ) {
                $new = '';
                foreach ( $style as $property => $value ) {
                    if ( $value == '' ) {
                        $new .= $property;
                    } else {
                        $new .= " {$property}: {$value};";
                    }
                }
                $output .= "{$selector} { {$new} }";
            }
            return $output;
        }

    }

    // B Blocks
    class BBlocks {
        protected static $_instance = null;

        function __construct() {
            add_action( 'wp_ajax_bBlocksPipeChecker', [$this, 'bBlocksPipeChecker'] );
            add_action( 'wp_ajax_nopriv_bBlocksPipeChecker', [$this, 'bBlocksPipeChecker'] );
            add_action( 'admin_init', [$this, 'registerSettings'] );
            add_action( 'rest_api_init', [$this, 'registerSettings'] );
            add_filter( 'block_categories_all', [$this, 'registerCategories'] );
            add_filter( 'upload_mimes', [$this, 'uploadMimes'] );
            add_filter(
                'wp_check_filetype_and_ext',
                [$this, 'wpCheckFiletypeAndExt'],
                10,
                5
            );
        }

        public static function instance() {
            if ( self::$_instance === null ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function bBlocksPipeChecker() {
            $nonce = $_POST['_wpnonce'] ?? null;
            if ( !wp_verify_nonce( $nonce, 'wp_ajax' ) ) {
                wp_send_json_error( 'Invalid Request' );
            }
            wp_send_json_success( [
                'isPipe' => BBlocks\Inc\BBlocksUtils::isPro(),
            ] );
        }

        function registerSettings() {
            register_setting( 'bBlocksUtils', 'bBlocksUtils', [
                'show_in_rest'      => [
                    'name'   => 'bBlocksUtils',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
                'type'              => 'string',
                'default'           => wp_json_encode( [
                    'nonce' => wp_create_nonce( 'wp_ajax' ),
                ] ),
                'sanitize_callback' => 'sanitize_text_field',
            ] );
        }

        function registerCategories( $categories ) {
            return array_merge( [[
                'slug'  => 'bBlocks',
                'title' => __( 'B Blocks', 'b-blocks' ),
            ]], $categories );
        }

        // Register categories
        //Allow some additional file types for upload
        function uploadMimes( $mimes ) {
            $mimes['glb'] = 'model/gltf-binary';
            // 3D Viewer
            $mimes['gltf'] = 'model/gltf-binary';
            // 3D Viewer
            $mimes['json'] = 'application/json';
            // Lottie Player
            $mimes['lottie'] = 'application/json';
            // Lottie Player
            return $mimes;
        }

        function wpCheckFiletypeAndExt(
            $data,
            $file,
            $filename,
            $mimes,
            $real_mime = null
        ) {
            // If file extension is 2 or more
            $f_sp = explode( '.', $filename );
            $f_exp_count = count( $f_sp );
            if ( $f_exp_count <= 1 ) {
                return $data;
            } else {
                $f_name = $f_sp[0];
                $ext = $f_sp[$f_exp_count - 1];
            }
            if ( $ext == 'glb' || $ext == 'gltf' ) {
                // 3D Viewer
                $type = 'model/gltf-binary';
                $proper_filename = '';
                return compact( 'ext', 'type', 'proper_filename' );
            } else {
                if ( $ext == 'json' || $ext === 'lottie' ) {
                    // Lottie Player
                    $type = 'application/json';
                    $proper_filename = '';
                    return compact( 'ext', 'type', 'proper_filename' );
                } else {
                    return $data;
                }
            }
        }

    }

    BBlocks::instance();
    // Require files
    require_once B_BLOCKS_DIR_PATH . 'inc/Utils.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/AdminMenu.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/getCSS.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/Ajax.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/enqueueScripts.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/row/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/row/column/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/posts/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/slider/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/section-heading/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/button/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/video/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/services/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/services/child/child.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/3d-viewer/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/image-gallery/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/image-comparison/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/flip-boxes/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/team-members/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/cards/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/pricing-table/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/price-lists/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/feature-boxes/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/info-box/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/lottie-player/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/chart/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/button-group/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/countdown/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/counters/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/mailto/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/gif/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/qr-code/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/social-share/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/animated-text/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/alert/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/shape-divider/block.php';
    require_once B_BLOCKS_DIR_PATH . 'inc/star-rating/block.php';
}