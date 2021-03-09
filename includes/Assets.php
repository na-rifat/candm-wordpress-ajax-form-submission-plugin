<?php

namespace candm;

/**
 * Registers essential assets
 */
class Assets {
    /**
     * Construct assets class
     */
    public $assets_path;
    function __construct() {
        $this->assets_path = CANDM_ASSETS;
        add_action( 'wp_enqueue_scripts', [$this, 'register'] );
        add_action( 'admin_enqueue_scripts', [$this, 'register'] );
        add_action( 'wp_enqueue_scripts', [$this, 'load'] );
        add_action( 'admin_enqueue_scripts', [$this, 'load'] );
    }

    /**
     * Initializes the class
     *
     * @return void
     */
    public function init() {

    }

    /**
     * Creates css file information
     *
     * @param  string $filename
     * @param  array  $deps
     * @return void
     */
    public function cssfile( $filename, $deps = [] ) {
        return ['src' => "{$this->assets_path}/css/{$filename}.css", 'version' => $this->cssversion( $filename ), 'deps' => $deps];
    }

    /**
     * Creates js file information
     *
     * @param  string $filename
     * @param  array  $deps
     * @return void
     */
    public function jsfile( $filename, $deps = [] ) {
        return ['src' => "{$this->assets_path}/js/{$filename}.js", 'version' => $this->jsversion( $filename ), 'deps' => $deps];
    }

    /**
     * Creates image file URL
     *
     * @param  [type] $filename
     * @return void
     */
    public static function imgfile( $filename ) {
        return CANDM_IMAGES . "/$filename";
    }

    /**
     * Returns JS file version
     *
     * @param  [type] $filename
     * @return void
     */
    public function jsversion( $filename ) {
        return 1;
        return filemtime( convert_path_slash( CANDM_PATH . "/assets/js/{$filename}.js" ) );
    }

    /**
     * Returns CSS file version
     *
     * @param  [type] $filename
     * @return void
     */
    public function cssversion( $filename ) {
        return 1;
        return filemtime( convert_path_slash( CANDM_PATH . "/assets/css/{$filename}.css" ) );
    }

    /**
     * Return scripts from array
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'candm-frontend-script' => $this->jsfile( 'frontend', ['jquery'] ),
        ];
    }

    /**
     * Return styles from array
     *
     * @return array
     */
    public function get_styles() {
        return [
            'candm-frontend-style' => $this->cssfile( 'frontend' ),
            'candm-admin-style'    => $this->cssfile( 'admin' ),
            'candm-fontawesome'    => [
                'src'     => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css',
                'version' => '5.15.2',
            ],
        ];
    }

    /**
     * Return localize variable from array
     *
     * @return array
     */
    public function get_localize() {
        return [
            'candm-frontend-script' => [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'home_url' => home_url(),
            ],
        ];
    }

    /**
     * Registers scripts, styles and localize variables
     *
     * @return void
     */
    public function register() {
        // Scripts
        $scripts = $this->get_scripts();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, ! empty( $script['version'] ) ? $script['version'] : false, true );

        }

        // Styles
        $styles = $this->get_styles();

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, ! empty( $style['version'] ) ? $style['version'] : false );
        }

        // Localization
        $localize = $this->get_localize();

        foreach ( $localize as $handle => $vars ) {
            wp_localize_script( $handle, 'candm', $vars );
        }
    }

    /**
     * Loads the scripts to frontend
     *
     * @return void
     */
    public function load() {
        wp_enqueue_style( 'candm-fontawesome' );

        if ( ! is_admin() ) {
            wp_enqueue_script( 'candm-frontend-script' );
            wp_enqueue_style( 'candm-frontend-style' );
            wp_enqueue_style( 'candm-fontawesome' );
        } else {
            wp_enqueue_style( 'candm-admin-style' );
        }
    }
}