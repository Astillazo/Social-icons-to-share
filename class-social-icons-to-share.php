<?php
/*
 * Plugin Name: Social Icons to Share
 * Plugin URI: https://antoniomadera.com
 * Version: 1.1
 * Description:
 * Author: Antonio Madera
 * Author URI: https://antoniomadera.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: social-icons-to-share
*/

class Social_Icons_To_Share {

    private const HOOK_INIT = 'init';
    private const HOOK_ADMIN_MENU_TO_DASHBOARD = 'admin_menu';
    private const HOOK_ENQUEUE_FRONT_SCRIPTS = 'wp_enqueue_scripts';
    private const HOOK_ENQUEUE_ADMIN_SCRIPTS = 'admin_enqueue_scripts';
    private const TEMPLATES_FOLDER = 'templates/';
    private const LIGHT_STRING = 'light';
    private const DARK_STRING = 'dark';
    private const COLOR_STRING = 'color';
    private const ICONS_LIGHT_TEMPLATE = 'frontend/social-icons-' . self::LIGHT_STRING . '-template.php';
    private const ICONS_DARK_TEMPLATE = 'frontend/social-icons-' . self::DARK_STRING . '-template.php';
    private const ICONS_COLOR_TEMPLATE = 'frontend/social-icons-' . self::COLOR_STRING . '-template.php';
    private const ASSETS_FOLDER = 'assets/';
    private const JS_FOLDER = self::ASSETS_FOLDER . 'js/';
    private const CSS_FOLDER = self::ASSETS_FOLDER . 'css/';
    private const PREFIX_PLUGIN_STRING = 'social-icons-to-share';
    private const JS_FRONTEND_FILE = self::PREFIX_PLUGIN_STRING . '-min.js';
    private const CSS_FRONTEND_FILE = self::PREFIX_PLUGIN_STRING . '-min.css';
    private const SHORTCODE_LIGHT_NAME = self::PREFIX_PLUGIN_STRING . '-' . self::LIGHT_STRING;
    private const SHORTCODE_DARK_NAME = self::PREFIX_PLUGIN_STRING . '-' . self::DARK_STRING;
    private const SHORTCODE_COLOR_NAME = self::PREFIX_PLUGIN_STRING . '-' . self::COLOR_STRING;
    private const MENU_ICON = 'dashicons-share';
    private const MENU_CAPABILITY = 'manage_options';
    private const MENU_SLUG = 'social-icons-to-share';
    private const ADMIN_PAGE = 'admin/info.php';
    private const PREFIX_VARNAME = 'sits_';
    private const PREFIX_VARNAMES = [
        self::PREFIX_VARNAME . 'top_',
        self::PREFIX_VARNAME . 'bottom_'
    ];
    private const DB_VARNAME = 'social_icons_to_share_general_options';
    private const VARNAME_FOR_ADMIN_TEMPLATE = self::PREFIX_VARNAME . 'options';
    private const MODE_TO_ADD_CONTENT = [
        self::LIGHT_STRING => '[' . self::SHORTCODE_LIGHT_NAME . ']',
        self::DARK_STRING => '[' . self::SHORTCODE_DARK_NAME . ']',
        self::COLOR_STRING => '[' . self::SHORTCODE_COLOR_NAME . ']'
    ];

    private function load_template( string $template_filename, bool $get_string = false ) {
        $file_pathname = plugin_dir_path( __FILE__ ) . self::TEMPLATES_FOLDER . $template_filename;

        if ( $get_string ) {
            return file_get_contents( $file_pathname );
        }

        load_template( $file_pathname );
    }

    private function add_assets() {
        $this->enqueue_css_shortcodes_files();
        $this->enqueue_frontend_js_files();
    }

    private function check_and_save_options() {
        if ( isset( $_POST ) && count( $_POST ) > 0 ) {
            $value_from_post = [];

            foreach ( $_POST as $varname => $value ) {
                if ( substr( $varname, 0, strlen( self::PREFIX_VARNAME ) ) === self::PREFIX_VARNAME ) {
                    foreach ( self::PREFIX_VARNAMES as $var ) {
                        if ( substr( $varname, 0, strlen( $var ) ) === $var ) {
                            $value_from_post[ $varname ] = $value;
                            break;
                        }
                    }
                }
            }

            $value_to_save = maybe_serialize( $value_from_post );
            update_option( self::DB_VARNAME, $value_to_save, false );
        }
    }

    private function get_options_from_db() {
        $options = get_option( self::DB_VARNAME );

        return maybe_unserialize( $options );
    }

    private function load_options_from_db_in_template() {
        $options = $this->get_options_from_db();

        if ( $options ) {
            set_query_var( self::VARNAME_FOR_ADMIN_TEMPLATE, $options );
        }
    }

    public function __construct() {
        if ( is_admin() ) {
            add_action( self::HOOK_ADMIN_MENU_TO_DASHBOARD, [ $this, 'add_admin_menu_to_dashboard' ] );
            add_action( self::HOOK_ENQUEUE_ADMIN_SCRIPTS, [ $this, 'enqueue_css_shortcodes_files'] );
        } else {
            add_filter( 'the_content', [ $this, 'show_icons_in_post_type' ] );
        }

        add_action( self::HOOK_INIT, [ $this, 'add_shortcodes' ] );
    }

    public function enqueue_frontend_js_files() {
        $js_url = plugins_url( self::JS_FOLDER . self::JS_FRONTEND_FILE, __FILE__ );

        wp_enqueue_script( self::PREFIX_PLUGIN_STRING, $js_url, [ 'jquery' ], '', true );
    }

    public function enqueue_css_shortcodes_files() {
        $css_url = plugins_url( self::CSS_FOLDER . self::CSS_FRONTEND_FILE, __FILE__ );

        wp_enqueue_style( self::PREFIX_PLUGIN_STRING, $css_url );
    }

    public function add_shortcodes() {
        add_shortcode( self::SHORTCODE_LIGHT_NAME, [ $this, 'shortcode_' . self::LIGHT_STRING ] );
        add_shortcode( self::SHORTCODE_DARK_NAME, [ $this, 'shortcode_' . self::DARK_STRING ] );
        add_shortcode( self::SHORTCODE_COLOR_NAME, [ $this, 'shortcode_' . self::COLOR_STRING ] );
    }

    public function shortcode_light() {
        $this->add_assets();

        return $this->load_template( self::ICONS_LIGHT_TEMPLATE, true );
    }

    public function shortcode_dark() {
        $this->add_assets();

        return $this->load_template( self::ICONS_DARK_TEMPLATE, true );
    }

    public function shortcode_color() {
        $this->add_assets();

        return $this->load_template( self::ICONS_COLOR_TEMPLATE, true );
    }

    public function add_admin_menu_to_dashboard() {
        add_menu_page(
            __( 'Social Icons', 'social-icons-to-share' ),
            __( 'Social Icons', 'social-icons-to-share' ),
            self::MENU_CAPABILITY,
            self::MENU_SLUG,
            [ $this, 'page_admin_menu' ],
            self::MENU_ICON
        );
    }

    public function page_admin_menu() {
        $this->check_and_save_options();
        $this->load_options_from_db_in_template();

        set_query_var( 'shortcode_' . self::LIGHT_STRING, self::SHORTCODE_LIGHT_NAME );
        set_query_var( 'shortcode_' . self::DARK_STRING, self::SHORTCODE_DARK_NAME );
        set_query_var( 'shortcode_' . self::COLOR_STRING, self::SHORTCODE_COLOR_NAME );
        $this->load_template( self::ADMIN_PAGE );
    }

    public function show_icons_in_post_type( string $content ) {
        if ( is_single() ) {
            $options = $this->get_options_from_db();

            if ( $options ) {
                $enqueue_js_assets = false;
                $post_type = get_post_type();
                $top_key = self::PREFIX_VARNAMES[ 0 ] . $post_type;
                $bottom_key = self::PREFIX_VARNAMES[ 1 ] . $post_type;

                if ( isset( $options[ $top_key ] ) && $options[ $top_key ] !== 'hidden' && isset( self::MODE_TO_ADD_CONTENT[ $options[ $top_key ] ] ) ) {
                    $content = self::MODE_TO_ADD_CONTENT[ $options[ $top_key ] ] . $content;
                    $enqueue_js_assets = true;
                }

                if ( isset( $options[ $bottom_key ] ) && $options[ $bottom_key ] !== 'hidden' && isset( self::MODE_TO_ADD_CONTENT[ $options[ $bottom_key ] ] ) ) {
                    $content .= self::MODE_TO_ADD_CONTENT[ $options[ $bottom_key ] ];
                    $enqueue_js_assets = true;
                }

                if ( $enqueue_js_assets ) {
                    $this->add_assets();
                }
            }
        }

        return $content;
    }
}

new Social_Icons_To_Share();
