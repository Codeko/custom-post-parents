<div class="wrap">
    <h2><?php _e('Custom Post Parents', 'custom-post-parents') ?></h2>
    <?php if (empty($available_schemas)): ?>
        <div class="error">
            <p><?php _e('No hierachical post types found.', 'custom-post-parents'); ?></p>
        </div>
    <?php endif ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'custom-post-parents-admin-menu' ); ?>
        <?php do_settings_sections( 'custom-post-parents-admin-menu' ); ?>
        <?php submit_button(); ?>
    </form>
</div>