<?php

namespace candm;

class Ajax {
    function __construct() {
        $this->register_hooks();
    }

    public function register_hooks() {
        candm_ajax( 'rsvp_submission', [$this, 'get_rsvp_submission'] );
    }

    /**
     * RSVP submission
     *
     * @return void
     */
    public function get_rsvp_submission() {
        $crud = new CRUD();

        $inserted   = $crud->create( \candm\Schema::DB_prefix() . "rsvp_submissions", 'rsvp_fields' );
        $data       = Schema::collect_from_post( 'rsvp_fields' );
        $data['id'] = $inserted;

        if ( is_wp_error( $inserted ) ) {
            wp_send_json_error(
                [
                    'msg' => $inserted->get_error_message(),
                ]
            );
            exit;
        } else {
            // Insert guests
            if ( candm_var( 'like_too_add_more_guest' ) == 'Yes' ) {
                $inserted_guests = $crud->create( \candm\Schema::DB_prefix() . 'rsvp_guests', 'guest_fields', ['form_id' => $inserted] );
            }
            // Send email
            $this->send_mail( $data );
            wp_send_json_success(
                [
                    'msg' => __( 'RSVP form submitted succesfully.', 'candm' ),
                ]
            );
            exit;
        }
    }

    public function send_mail( $data ) {
        $title   = get_option( 'blogname' );
        $link    = admin_url( sprintf( '/admin.php?page=candm-rsvp-submissions&template=single&id=%d', $data['id'] ) );
        $to      = get_option( 'rsvp-notification-emails' );
        $subject = "New RSVP form submission";
        $body    = "Hi, there is a new submission of RSVP form. Details described below:<br><br>
            First name: {$data['first_name']}<br>
            Last name: {$data['last_name']}<br>
            Email: {$data['email']}<br>
            Phone: {$data['phone']}<br>
            Does have guests?: {$data['like_too_add_more_guest']}<br>
            Check it out from here: <a href='{$link}'>{$data['first_name']}</a>
            <br><br>
            -{$title}<br>
            ";
        $headers   = [];
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = sprintf( 'From: %s <%s>', get_option( 'blogname', 'C&M' ), 'bot@claudiamatija2021.eu' );
        wp_mail( $to, $subject, $body, $headers );
    }
}