<div class="wrap">
    <h1><?php esc_html_e( BASE_NAME ); ?> Settings</h1>

    <form method="post" action="" >
    <?php settings_fields( 'base_settings' ); ?>
    <?php do_settings_sections( 'base_settings' ); ?>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
    </p>
    </form>
</div>
