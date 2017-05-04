<?php
/* Plugin Name: Custom Post Parents
  Description: Allow to set any post type as post parent
  Text Domain: custom-post-parents
  Domain Path: /lang
  Version: 0.1
  License: GPLv2
 */

function custom_post_parents_deactivation(){
    //Clear custom rewrites
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'custom_post_parents_deactivation' );

define('CUSTOM_POST_PARENTS_PATH', dirname(__FILE__));
define('CUSTOM_POST_PARENTS_FILE', __FILE__);

foreach ( glob( plugin_dir_path( __FILE__ ) . "includes/*.php" ) as $file ) {
    include_once $file;
}

CustomPostParentsAdminPage::instance();
CustomPostParentsMetaBox::instance();
CustomPostParents::instance();
