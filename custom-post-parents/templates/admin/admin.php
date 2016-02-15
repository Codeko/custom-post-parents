<div class="wrap">

    <h2><?php _e('Post parents', 'custom-post-parents') ?></h2>
    <?php if (empty($available_schemas)): ?>
        <div class="error">
            <p><?php _e('No se encontro ningun Custom Post Types que tenga el parametro hierarchical activado.', 'custom-post-parents'); ?></p>
            <strong><?php _e('Implementacion de ejemplo:', 'custom-post-parents'); ?>:</strong>
            <pre>
register_post_type('news',
    array(
        'labels' => array(
        'name' => __('News'),
        'singular_name' => __('News item')
    ),
    'public' => true,
    'hierarchical' => true
    )
)
// do not add 'rewrite' => array('slug' => '...').
            </pre>
        </div>
    <?php endif ?>


    <form method="post" action="options.php">

        <?php settings_fields( 'custom-post-parents-admin-menu' ); ?>
        <?php do_settings_sections( 'custom-post-parents-admin-menu' ); ?>
        <?php submit_button(); ?>

    </form>
</div>