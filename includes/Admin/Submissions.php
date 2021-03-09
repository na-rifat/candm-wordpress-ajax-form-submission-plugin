<?php

namespace candm\Admin;

class Submissions extends \WP_List_Table {
    function __construct( $posts ) {
        $GLOBALS['comparrot_import_log'] = $posts;
        parent::__construct( array(
            'singular' => 'post',
            'plural'   => 'posts',
            'ajax'     => false,
        ) );

        $this->_show();
    }

    /**
     * Get columns
     *
     * @return void
     */
    function get_columns() {
        return [
            'first_name'                   => __( 'First name', 'candm' ),
            'email'                        => __( 'Email', 'candm' ),
            'phone'                        => __( 'Phone', 'candm' ),
            'like_too_add_more_guest'      => __( 'Have guest(s)?', 'candm' ),
            'help_accommodation_dubrovnik' => __( 'Help accommodation Dubrovnik?', 'candm' ),
            'attending_pre_wedding_day'    => __( 'Attending pre wedding day?', 'candm' ),
            'actions'                      => __( 'Action', 'candm' ),
        ];
    }

    /**
     *
     * Sortable columns list
     *
     * @return void
     */
    function get_sortable_columns() {
        $sortable_columns = [

        ];
        return $sortable_columns;
    }

    /**
     * Formats and sends default comments
     *
     * @param  [type] $item
     * @param  [type] $column_name
     * @return void
     */
    protected function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'phone':
                return "<a href='tel:{$item->phone}'>{$item->phone}</a>";
            case 'email':
                return "<a href='mailto:{$item->email}'>{$item->email}</a>";
            case 'actions':
                $url = admin_url( "/admin.php?page=candm-rsvp-submissions&template=single&id={$item->id}" );
                return "<a href='{$url}' class='button' data-id='{$item->id}'>View</a>";
                break;
            default:
                return isset( $item->$column_name ) ? $item->$column_name : '';
                break;
        }
    }

    /**
     * Prepares items
     *
     * @return void
     */
    public function prepare_items() {
        $column   = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $column, $hidden, $sortable );

        $this->items = is_object( $GLOBALS['comparrot_import_log'] ) ? $GLOBALS['comparrot_import_log'] : (object) [];
        $this->items = $GLOBALS['comparrot_import_log'];

        $size = count( $this->items );

        $this->set_pagination_args( array(
            'total_items' => $size,
            'per_page'    => $size,
        ) );
    }

    /**
     * Generates content for a single row of the table.
     *
     * @param object|array $item The current item
     */
    public function single_row( $item ) {
        echo "<tr>";
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    /**
     * Creates the list
     *
     * @param  [type] $list
     * @return void
     */
    public function _show() {
        $this->prepare_items();
        $this->display();
    }
}