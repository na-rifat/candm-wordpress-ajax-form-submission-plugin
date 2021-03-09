<?php

namespace candm\Widgets;

/**
 * Manages Widgets
 */
class Manager {

    function __construct() {
        add_action( 'elementor/widgets/widgets_registered', [$this, 'register_elementor_widgets'], 99 );
        add_action( 'elementor/elements/categories_registered', [$this, 'register_elementor_categories'] );
    }

    public function register() {

    }

    /**
     * Registers elementor widgets
     *
     * @return void
     */
    public function register_elementor_widgets() {
        /**
         * Geomify button
         */
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Items\Elementor\Contact() );
    }

    /**
     * Registers candm category for elements section
     *
     * @return void
     */
    public function register_elementor_categories( $element_manager ) {
        $element_manager->add_category(
            'candm',
            [
                'title' => __( 'candm', 'candm' ),
                'icon'  => 'fab fa-google',
            ]
        );
        return $element_manager;
    }
}