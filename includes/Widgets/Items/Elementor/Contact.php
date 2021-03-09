<?php

namespace candm\Widgets\Items\Elementor;
use Elementor\Controls_Manager as Controls;
use Elementor\Widget_Base as Base;

class Contact extends Base {

    public function get_name() {
        return 'candm-contacnt';
    }

    public function get_title() {
        return __( 'Contact', 'candm' );
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_categories() {
        return ['candm'];
    }

    public function get_keywords() {
        return ['candm', 'rafalo', 'contact', 'contact button'];
    }

    /**
     * Registers controls for
     *
     * @return void
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'contents',
            [
                'label' => __( 'Contents', 'candm' ),
                'tab'   => Controls::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label'   => __( 'Image', 'candm' ),
                'type'    => Controls::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'person_name',
            [
                'label'       => __( 'Person name', 'candm' ),
                'type'        => Controls::TEXT,
                'default'     => __( 'Mr. Person', 'candm' ),
                'description' => __( 'Name of the person', 'candm' ),
            ]
        );

        $this->add_control(
            'person_title',
            [
                'label'       => __( 'Person title', 'candm' ),
                'type'        => Controls::TEXT,
                'default'     => __( 'Planner', 'candm' ),
                'description' => __( 'Title of the person', 'candm' ),
            ]
        );

        $this->add_control(
            'email',
            [
                'label'       => __( 'Email', 'candm' ),
                'type'        => Controls::TEXT,
                'default'     => 'mail@domain.com',
                'description' => __( 'Email fo the person', 'candm' ),
            ]
        );
        $this->add_control(
            'phone',
            [
                'label'       => __( 'Phone', 'candm' ),
                'type'        => Controls::TEXT,
                'default'     => '+ XX XXX XXX XXX',
                'description' => __( 'Phone number of the person', 'candm' ),
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'           => __( 'Alignment', 'geomify' ),
                'type'            => Controls::CHOOSE,
                'devices'         => ['desktop', 'tablet', 'mobile'],
                'options'         => [
                    'left'   => [
                        'title' => __( 'Left', 'geomify' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'geomify' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'geomify' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'desktop_default' => 'left',
                'tablet_default'  => 'left',
                'mobile_default'  => 'center',
                'selectors'       => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                ],
            ],

        );
        $this->end_controls_section();
    }

    /**
     * Renders the element to the frontend
     *
     * @return void
     */
    protected function render() {
        $s = $this->get_settings_for_display();

        $this->add_inline_editing_attributes(
            'person_name',
        );
        $this->add_inline_editing_attributes(
            'person_title'
        );
        $this->add_inline_editing_attributes(
            'email'
        );
        $this->add_inline_editing_attributes(
            'phone'
        );

        $this->add_render_attribute(
            'email',
            [
                'class' => 'candm-contact-eamil',
                'href'  => sprintf( 'mailto:%s', $s['email'] ),
            ]
        );
        $this->add_render_attribute(
            'phone',
            [
                'class' => 'candm-contact-phone',
                'href'  => sprintf( 'tel:%s', $s['phone'] ),
            ]
        );

        $person_name_attr  = $this->get_render_attribute_string( 'person_name' );
        $person_title_attr = $this->get_render_attribute_string( 'person_title' );
        $email             = $this->get_render_attribute_string( 'email' );
        $phone             = $this->get_render_attribute_string( 'phone' );

        $el = sprintf( '<div class="candm-contact">
        <img src="%s" />
        <h2 %s >%s</h2>
        <span %s >%s</span>
        <a %s >%s</a>
        <a %s >tel: %s</a>
        </div>',
            $s['image']['url'],
            $person_name_attr,
            $s['person_name'],
            $person_title_attr,
            $s['person_title'],
            $email,
            $s['email'],
            $phone,
            $s['phone']
        );
        echo $el;
    }

    protected function _content_template() {

    }
}