<?php

class CustomPostParentsAdminPage {

    const ADMIN_PAGE_NAME = 'custom-post-parents-admin-menu';

    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new CustomPostParentsAdminPage();
        }
        return $inst;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_enqueue_scripts', function ($hook_suffix) {
            if ('settings_page_custom-post-parents-admin-menu' != $hook_suffix){
                return;
            }
             
            wp_enqueue_script( 'custom_post_parents_admin', plugins_url('/js/custom-post-parents-admin.js', CUSTOM_POST_PARENTS_FILE),array('jquery'),"0.1");
            $postParentConfig = array(
                'selected' => CustomPostParentsGlobal::instance()->get_post_type_schema(),
                'lookup' => CustomPostParentsGlobal::instance()->get_lookup_schema(),
                'defaultOption'=>__("Choose option",'custom-post-parents')
            );
            wp_localize_script('custom_post_parents_admin','postParentConfig',$postParentConfig);
        });
        add_filter('plugin_action_links_' . plugin_basename(CUSTOM_POST_PARENTS_PATH) . '/custom-post-parents.php', array($this, 'filter_plugin_links'));
    }
    public function filter_plugin_links($links) {
        $settings_link = '<a href="' . get_admin_url(null, 'options-general.php?page=' . self::ADMIN_PAGE_NAME) . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    public function admin_init() {


        add_settings_section(
                'custom-post-parents-setting_section', __('Configuracion', 'custom-post-parents'), function () {
            CustomPostParentsGlobal::instance()->render('admin/admin-section-intro.php');
        }, self::ADMIN_PAGE_NAME
        );

        // Add the field with the names and function to use for our new
        // settings, put it in our new section
        add_settings_field(
                'custom-post-parents-post_types', __('Post parent configuration', 'custom-post-parents'), array($this, 'settings_field_view'), self::ADMIN_PAGE_NAME, 'custom-post-parents-setting_section'
        );
        // Register the setting for $_POST to work.
        register_setting(self::ADMIN_PAGE_NAME, 'custom-post-parents-post_types');
        load_plugin_textdomain('custom-post-parents', false,"custom-post-parents/lang");
    }

    public function settings_field_view() {

        CustomPostParentsGlobal::instance()->render('admin/admin-field-post_types.php', array(
            'selected' => CustomPostParentsGlobal::instance()->get_post_type_schema(),
            'lookup' => CustomPostParentsGlobal::instance()->get_lookup_schema(),
            'available_schemas' => CustomPostParentsGlobal::instance()->get_available_post_type_schemas()
        ));
    }

    public function admin_menu() {
        add_options_page(
                __('Custom Post Parents', 'custom-post-parents'), __('Custom Post Parents', 'custom-post-parents'), 'manage_options', 'custom-post-parents-admin-menu', array($this, 'options')
        );
    }

    public function options() {

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            // We need to rewrite.
            flush_rewrite_rules();
        }

        CustomPostParentsGlobal::instance()->render('admin/admin.php', array(
            'available_schemas' => CustomPostParentsGlobal::instance()->get_available_post_type_schemas(),
        ));
    }

}