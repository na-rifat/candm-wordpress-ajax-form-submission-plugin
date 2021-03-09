<?php

namespace candm\Frontend;

class Frontend {
    function __construct() {
        add_shortcode( 'rsvp-form', [$this, 'rsvp_form'] );
    }

    public function rsvp_form() {
        ob_start();
        include __DIR__ . "/views/rsvp-form.php";
        return ob_get_clean();
    }

}