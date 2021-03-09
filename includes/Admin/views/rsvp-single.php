<?php

    use candm\Schema;

    $id = isset( $_GET['id'] ) ? $_GET['id'] : 0;

    $data = \candm\CRUD::retrieve( \candm\Schema::DB_prefix() . 'rsvp_submissions', true );

    if ( empty( $data ) ) {
    ?>
<h3>No data found!</h3>
<?php
    } else {
        if ( $data->like_too_add_more_guest == 'Yes' ) {
            $prefix = \candm\CRUD::DB()->prefix;
            $guests = \candm\CRUD::DB()->get_row(
                \candm\CRUD::DB()->prepare(
                    "SELECT * FROM {$prefix}rsvp_guests WHERE form_id=%d",
                    $id
                )
            );

        }
        // View level started
    ?>
<br>
<a href="<?php echo admin_url( '/admin.php?page=candm-rsvp-submissions' ) ?> " class="button">&laquo; Go back</a>
<h2>RSVP - <?php echo $data->first_name ?></h2>
<hr>
<table class="form-table">
    <tr>
        <th>First name</th>
        <td><?php echo $data->first_name ?></td>
    </tr>
    <tr>
        <th>Last name</th>
        <td><?php echo $data->last_name ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><a href="mailto:<?php echo $data->email ?>"><?php echo $data->email ?></a></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><a href="tel:<?php $data->phone?>"><?php echo $data->phone ?></a></td>
    </tr>
    <tr>
        <th>Dinner meal preference</th>
        <td><?php echo $data->dinner_meal_preference ?></td>
    </tr>
    <tr>
        <th>Have guests?</th>
        <td><?php echo $data->like_too_add_more_guest ?></td>
    </tr>
    <tr>
        <th>Do you or your guests have any dietary restrictions?</th>
        <td><?php echo $data->does_guests_have_dietary ?></td>
    </tr>
    <tr>
        <th>Do you require help with booking your accommodation in Dubrovnik?</th>
        <td><?php echo $data->help_accommodation_dubrovnik ?></td>
    </tr>
    <tr>
        <th>Would you be attenbding pre-weddiing day get together?</th>
        <td><?php echo $data->attending_pre_wedding_day ?></td>
    </tr>
    <tr>
        <th>Comments</th>
        <td><?php echo $data->commetns ?></td>
    </tr>
</table>
<?php
    if ( ! empty( $guests ) ) {

            $first_names = unserialize( $guests->guest_first_name );
            $last_names  = unserialize( $guests->guest_last_name );
            $dinner_meal = unserialize( $guests->guestdinner_meal_preference );
        ?>
<h2>Guests</h2>
<hr>
<div class="guest-list">
    <table>
        <thead>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Dinner meal preference</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for($i = 0; $i < count($first_names); $i++){
                ?>
                    <tr>
                        <td><?php echo $first_names[$i] ?></td>
                        <td><?php echo $last_names[$i] ?></td>
                        <td><?php echo $dinner_meal[$i] ?></td>
                    </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
    }

}