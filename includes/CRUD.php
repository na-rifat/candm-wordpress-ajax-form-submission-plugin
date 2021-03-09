<?php

namespace candm;

class CRUD {
    function __construct() {
        $this->init();
    }

    /**
     * Initializes the class
     *
     * Creates self schema object
     *
     * @return void
     */
    function init() {
        $schema       = new Schema();
        $this->schema = $schema;
    }

    /**
     * Creates an instance of database class
     *
     * @return mixed
     */
    public static function DB() {
        global $wpdb;
        return $wpdb;
    }

    /**
     * Inserts a new record in database
     *
     * @param  mixed        $table_name
     * @param  mixed        $schema_name
     * @return object|int
     */
    public function create( $table_name, $schema_name, $additional_fields = [] ) {

        // Collecting post data
        $data = $this->schema->collect_from_post( $schema_name );

        // Error checking
        if ( is_wp_error( $data ) ) {
            return $data;
        }

        // Data type
        $data_type = Schema::data_col_type_schema( $this->schema->get( $schema_name ) );

        // Additional fields
        if ( ! empty( $additional_fields ) ) {
            foreach ( $additional_fields as $name => $value ) {
                $data[$name] = $value;
            }
        }

        // Insert
        $insert_id = self::DB()->insert(
            $table_name,
            $data,
            $data_type
        );

        // Value return
        if ( is_wp_error( $insert_id ) ) {
            return new \WP_Error( 'crud-insert-failed', __( 'Failed to insert a record to the database', 'candm' ), $data );
        }
        return self::DB()->insert_id;
    }

    /**
     * Retrives data from database
     *
     * Single retrieve supports
     *
     * Multple retrieve supports
     *
     * @param  string  $table_name
     * @param  string  $single
     * @return mixed
     */
    public static function retrieve( $table_name, $single = false ) {

        if ( $single == false) {
            return self::DB()->get_results(
                "SELECT * FROM {$table_name}",
            );
        } else {
            return self::DB()->get_row(
                "SELECT * FROM {$table_name}",
            );
        }
    }

    public function update( $table_name, $schema_name, $identifier_col ) {

    }

    public function delete() {

    }

    public function list() {

    }

    /**
     * Creates a table inside database
     *
     * @param  [type] $schema
     * @return void
     */
    public static function create_table( $schema ) {
        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

}