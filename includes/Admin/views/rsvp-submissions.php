<h2>RSVP submissions</h2>
<hr>
<form action="<?php echo admin_url( '/admin.php?page=candm-rsvp-submissions' ) ?>" method="POST">
    <table class="form-table">
        <tr>
            <th>Notification emails</th>
            <td><input type="text" class="regular-text" name="rsvp-notification-emails" id="rsvp-notification-emails"
                    value="<?php echo get_option( 'rsvp-notification-emails', '' ) ?>"></td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" value="Save" class="button"></td>
        </tr>
    </table>
</form>
<?php
new \candm\Admin\Submissions( \candm\CRUD::retrieve( \candm\Schema::DB_prefix() . 'rsvp_submissions' ) );