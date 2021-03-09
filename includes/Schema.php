<?php
namespace candm;

class Schema {
    const prefix     = 'candm';
    const data_types = [
        's' => ['text', 'longtext', 'varchar'],
        'd' => ['number', 'longint', 'int', 'integer'],
        'f' => ['float', 'double'],
    ];
    const checkbox_off = 'No';
    const __           = 'candm';

    function __construct() {
        $this->__build();

    }

    /**
     * Builds the initial schemas
     *
     * @return void
     */
    public function __build() {
        $this->rsvp_fields = [
            'first_name'                   => [
                'title'    => __( 'First name', self::__ ),
                'required' => true,
            ],
            'last_name'                    => [
                'title'    => __( 'Last name', self::__ ),
                'required' => true,
            ],
            'email'                        => [
                'title'    => __( 'Email', self::__ ),
                'required' => true,
            ],
            'phone'                        => [
                'title'    => __( 'Phone', self::__ ),
                'required' => true,
            ],
            'dinner_meal_preference'       => [
                'title' => __( 'Dinner meal preferences', self::__ ),
            ],
            'like_too_add_more_guest'      => [
                'title' => __( 'Would you like to add more guests to your group?', self::__ ),
                'type'  => 'checkbox',
            ],
            'does_guests_have_dietary'     => [
                'title' => __( 'Do you or your guests have any dietary restrictions?', self::__ ),
                'type'  => 'textarea',
            ],
            'help_accommodation_dubrovnik' => [
                'title' => __( 'Do you require help with booking your accommodation in Dubrovnik?', self::__ ),
                'type'  => 'checbox',
            ],
            'attending_pre_wedding_day'    => [
                'title' => __( 'Would you be attenbding pre-weddiing day get together?', self::__ ),
                'type'  => 'checkbox',
            ],
            'comments'                     => [
                'title' => __( 'Any questions or comments for us or the wedding planners?', self::__ ),
                'type'  => 'textarea',
            ],
        ];

        $this->guest_fields = [
            'guest_first_name'            => [
                'title' => __( 'First name', self::__ ),
            ],
            'guest_last_name'             => [
                'title' => __( 'Last name', self::__ ),
            ],
            'guestdinner_meal_preference' => [
                'title' => __( 'Dinner meal preference', self::__ ),
            ],
            'form_id'                     => [
                'title'     => __( 'Form ID', self::__ ),
                'type'      => 'number',
                'data_type' => 'int',
            ],
        ];
        $this->guest_fields_form_id = [
            'form_id' => [
                'type'      => 'number',
                'data_type' => 'int',
            ],
        ];
    }

    public function install_database() {
        CRUD::create_table( self::database_create_table_schema( 'rsvp_submissions', self::get_static_core( 'rsvp_fields' ) ) );
        CRUD::create_table( self::database_create_table_schema( 'rsvp_guests', self::get_static_core( 'guest_fields' ) ) );
    }

    public static function _get_settings() {

    }

    public static function _set_settings() {

    }

    /**
     * Stores a Schema in database
     *
     *
     * @param  string $name
     * @param  array  $schema
     * @return void
     */
    public static function set( $name, $schema ) {
        update_option( self::prefix . $name, $schema );
    }

    /**
     * Returns database stored schama
     *
     * Default on empty in database
     *
     *
     * @param  string $name
     * @return void
     */
    public function get( $name ) {
        $schema = get_option( self::prefix, $this->$name );

        // Check for static changes
        if ( ! count( $schema ) == count( $this->$name ) ) {
            foreach ( $schema as $key => $value ) {
                if ( ! isset( $this->$name[$key] ) ) {
                    array_push( $schema, $this->$name[$key] );
                }
            }
            self::set( $name, $schema );
        }

        return $schema;
    }

    /**
     * Returns static schema
     *
     * Basically the core Array
     *
     * @param  [type] $name
     * @return void
     */
    public static function get_static_core( $name ) {
        $self = new self();
        return $self->$name;
    }

    /**
     * Returns a schema from database via static method
     *
     * @param  string  $name
     * @return mixed
     */
    public static function get_static( $name ) {
        $self = new self();
        return $self->get( $name );
    }

    /**
     * Collects schema data from POST method
     *
     * @param  [type] $schema
     * @return void
     */
    public static function collect_from_post( $name ) {
        $result = [];
        $schema = self::get_static( $name );

        foreach ( $schema as $key => $value ) {
            $value = self::merge_single_field_schema( $value );

            if ( $value['required'] && empty( $_POST[$key] ) && $value['type'] != 'checbox' ) {
                return new \WP_Error( 'required-field-missing', __( 'One or more required field(s) are missing', self::__ ) );
            }

            switch ( $value['type'] ) {
                case 'checkbox':
                    $result[$key] = ! isset( $_POST[$key] ) ? self::checkbox_off : $_POST[$key];
                    break;
                default:
                    $result[$key] = isset( $_POST[$key] ) ? $_POST[$key] : '';
                    break;
            }
        }

        $data = [];
        foreach ( $result as $key => $value ) {
            switch ( gettype( $value ) ) {
                case 'array':
                    $data[$key] = serialize( $value );
                    break;
                default:
                    $data[$key] = $value;
                    break;
            }
        }

        return $data;
    }

    /**
     * Builds database supported schema
     *
     * @return void
     */
    public static function database_create_table_schema( $table_name, $schema, $primary_key = 'id' ) {
        $prefix          = CRUD::DB()->prefix;
        $charset_collate = CRUD::DB()->get_charset_collate();

        $qry = "CREATE TABLE IF NOT EXISTS `{$prefix}{$table_name}` (
        `{$primary_key}` int(255) NOT NULL AUTO_INCREMENT, ";

        foreach ( $schema as $field => $val ) {
            $qry .= self::database_single_field_schema( $field, $val );
        }

        $qry .= "PRIMARY KEY (`{$primary_key}`) ) {$charset_collate}";

        return $qry;
    }

    /**
     * Merge single field schema values
     *
     * @param  [type] $schema
     * @return void
     */
    public static function merge_single_field_schema( $schema ) {
        $defaults = [
            'title'     => __( 'Input field', self::__ ),
            'type'      => 'text',
            'data_type' => 'longtext',
            'required'  => false,
            'options'   => [],
            'class'     => [],
            'value'     => '',
        ];

        return wp_parse_args( $schema, $defaults );
    }

    /**
     * Creates single database field schema
     *
     * @param  [type] $schema
     * @return void
     */
    public static function database_single_field_schema( $field_name, $schema ) {
        $schema = self::merge_single_field_schema( $schema );

        $null = $schema['required'] == true ? 'NOT NULL' : 'DEFAULT NULL';

        return "`{$field_name}` {$schema['data_type']} {$null}, ";
    }

    /**
     * Creates input fields from schema
     *
     * @return void
     */
    public function create_input_fields( $form_name, $schema, $start = 0, $end = 0 ) {
        $form = "<form data-name='{$form_name}' method='POST' action=''>";

        foreach ( $schema as $field_name => $val ) {
            $val      = self::merge_single_field_schema( $val );
            $classes  = implode( ' ', $val['class'] );
            $required = $val['required'] == true ? 'required' : '';

            switch ( $val['type'] ) {
                case 'text':
                case 'number':
                case 'date':
                case 'url':
                case 'email':
                case 'color':
                    $form .= "<div class='input-group {$classes}'>
                    <label for='{$field_name}'>{$val['title']}</label>
                    <input type='{$val['type']}' name='{$field_name}' id='{$field_name}' value='{$val['value']}' {$required}/>
                    </div>";
                    break;
                case 'select':

            }
        }
    }

    /**
     * Converts columns to data type for SQL operations
     *
     * @return array
     */
    public static function data_col_type_schema( $schema ) {
        $result = [];

        foreach ( $schema as $key => $value ) {
            switch ( $value['data_type'] ) {
                case 'longtext':
                case 'text':
                case 'varchar':
                    $result[] = '%s';
                    break;
                case 'integer':
                case 'number':
                case 'int':
                case 'longint':
                    $result[] = '%d';
                    break;
                case 'float':
                case 'double':
                    $result[] = '%f';
                    break;
                default:
                    $result[] = '%s';
                    break;
            }
        }

        return $result;
    }

    public static function DB_prefix() {
        return CRUD::DB()->prefix;
    }

}
