<?php

namespace candm\Admin;

class Admin {
    function __construct() {
        add_action( 'admin_menu', [$this, 'register_menu_pages'] );
        if ( isset( $_POST['rsvp-notification-emails'] ) ) {
            update_option( 'rsvp-notification-emails', $_POST['rsvp-notification-emails'] );
        }
    }

    public function register_menu_pages() {
        add_menu_page( __( 'RSVP submissions', 'candm' ), __( 'RSVP', 'candm' ), 'manage_options', 'candm-rsvp-submissions', [$this, 'rsvp_submissions'], 'dashicons-format-aside', 2 );
    }

    public function rsvp_submissions() {
        switch ( isset( $_GET['template'] ) ? $_GET['template'] : 'list' ) {
            case 'single':
                include __DIR__ . "/views/rsvp-single.php";
                break;
            default:
                include __DIR__ . "/views/rsvp-submissions.php";
                break;
        }

    }

}