<?php
/**
 * Geomify
 *
 *
 * @wordpress-plugin
 * Plugin Name:       CandM
 * Plugin URI:        https://rafalotech.com/plugins/wp/geomify
 * Description:       Handles CandM functions
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rafalo tech
 * Author URI:        https://rafalotech.com
 * Text Domain:       candm
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 *
 *
 *
 *
 * @package           PluginPackage
 *
 * @author            Rafalo tech
 * @copyright         2021 Rafalo tech
 * @license           GPL-2.0-or-later
 */

namespace candm;

use candm\Frontend\Frontend;

require_once "vendor/autoload.php";

/**
 * Handles theme functions
 */
class Candm {
    function __construct() {
        $this->define_constants();
        add_action( 'plugins_loaded', [$this, 'init'] );
        $schema = new Schema();
        register_activation_hook( __FILE__, [$schema, 'install_database'] );
    }

    /**
     * Creates instance of the class
     *
     * @return void
     */
    public static function instance() {
        static $instance = false;
        if ( ! $instance ) {
            $instance = new self();
        }
    }

    /**
     * Initializes the classes
     *
     * @return void
     */
    public function init() {
        $assets  = new Assets();
        $fronted = new Frontend();
        if ( is_admin() ) {
            $admin = new Admin\Admin();
        }

        $schema = new Schema();
        $crud   = new CRUD();
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $ajax = new Ajax();
        }

        $widets = new Widgets\Manager();
    }

    /**
     * Defines the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'CANDM_PATH', __DIR__ );
        define( 'CANDM_FILE', __FILE__ );
        define( 'CANDM_PLUGIN_PATH', plugins_url( '', CANDM_FILE ) );
        define( 'CANDM_ASSETS', CANDM_PLUGIN_PATH . '/assets' );
        define( 'CANDM_JS', CANDM_ASSETS . '/js' );
        define( 'CANDM_CSS', CANDM_ASSETS . '/css' );
        define( 'CANDM_IMAGES', CANDM_ASSETS . '/img' );
        define( 'CANDM_FUNCTIONS', __DIR__ . '/includes/functions.php' );
    }

}

function create() {
    return Candm::instance();
}

create();
?>