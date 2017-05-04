<?php

class CustomPostParentsMetaBox {

    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new CustomPostParentsMetaBox();
        }
        return $inst;
    }

    private function __construct() {
        add_action('admin_menu', function() {
            $optionSchemas = CustomPostParentsGlobal::instance()->get_post_type_schema();
            // RECORRER CUSTOM POST TYPES CON PADRES ESPECIALES Y DESHABILITAR EL PAGEPARENTDIV PARA CADA UNO DE ELLOS
            foreach ($optionSchemas as $pageType => $v) {
                remove_meta_box('pageparentdiv', $pageType, 'normal');
            }
        });

        add_action('add_meta_boxes', array($this, 'add_meta_boxes_add'));
    }

    public function add_meta_boxes_add() {
        $optionSchemas = CustomPostParentsGlobal::instance()->get_post_type_schema();

        $isFirst = true;
        foreach ($optionSchemas as $ownerPageType => $schema) {
            add_meta_box(
                    'custom-post-parents-metabox', 'page' == $ownerPageType ? __('Page Attributes') : __('Attributes'), array($this, 'add_meta_boxes_add_callback'), $ownerPageType, 'side', 'high', array(
                'schema' => $schema
                    )
            );
            $isFirst = false;
        }
    }

    public function add_meta_boxes_add_callback($post, $box) {
        global $post;
        $schema = $box['args']['schema'];
        $lookup = CustomPostParentsGlobal::instance()->get_lookup_schema();
        $post_type_object = get_post_type_object($post->post_type);

        $isFirst = true;
        ?>
        <label class="screen-reader-text" for="parent_id"><?php _e('Parent') ?></label>
        <?php
        echo "<p><strong>" . __('Parent', 'custom-post-parents') . ":</strong>
                <span id='parent_id_status'>
                " . ($post->post_parent ? get_the_title($post->post_parent) : __('(no parent)') ) . "
                </span></p>
                <input type='hidden' id='parent_id' name='parent_id' value='{$post->post_parent}' />";

        echo "<p><strong>" . __('Post type') . ": </strong> 
        <select name='my_meta_box_post_type' id='my_meta_box_post_type'>";
        $post_types = get_post_types('', 'objects');
        foreach ($post_types as $post_type) {
            if ($post_type->hierarchical == true && in_array($post_type->name, $schema["values"])) {
                $pater = get_post_type($post->post_parent);
                $selected = ($pater === $post_type->name) ? "selected" : "";
                echo "<option value = '" . esc_attr($post_type->name) . "' $selected >";
                echo esc_html($post_type->label);
                echo "</option>";
            }
        }
        echo "</select>";
        echo "</p>";

        foreach ($schema['values'] as $k => $pageType) :
            $pages = wp_dropdown_pages(array(
                'post_type' => $pageType,
                'exclude_tree' => $post->ID,
                'selected' => $post->post_parent,
                'name' => 'selection-parent-id-' . $pageType,
                'id' => 'selection-parent-id-' . $pageType,
                'show_option_none' => __("None"),
                'option_none_value' => '0',
                'hierarchical' => 0,
                'sort_column' => 'menu_order, post_title',
                'echo' => 0)
            );
            // Only ONCE:
            if ($isFirst):
                ?>
                <script>
                    jQuery(function ($) {
                        $("select[name^='selection-parent-id-']").each(function () {
                            $(this).change(function () {
                                $('#parent_id').val($(this).val());
                                var status = $('#parent_id_status');
                                var newText = $(this).children(':selected').text();
                                status.text(newText);
                                $("select[name^='selection-parent-id-']").not(this).val('');
                            });
                        });
                    });
                </script>
                <?php
            endif;


            $label = $lookup[$pageType];
            echo "<div id='" . $pageType . "-container' class='padres-containers'><p><strong>{$label}: </strong> ";
            if (!empty($pages)) {
                echo $pages;
            } else {
                echo __('none');
            }
            echo "</p></div>";

            $isFirst = false;
        endforeach;
        ?><script>
                    jQuery(function ($) {
                        var tipo = $('#my_meta_box_post_type :selected').val();
                        $('.padres-containers').hide();
                        $("#" + tipo + "-container").show();
                        $('#my_meta_box_post_type').click(function () {
                            var tipo = $('#my_meta_box_post_type :selected').val();
                            $('.padres-containers').hide();
                            $("#" + tipo + "-container").show();
                        });
                    });
        </script>
        <hr/>
        <?php
        if ('page' == $post->post_type && 0 != count(get_page_templates($post)) && get_option('page_for_posts') != $post->ID) {
            $template = !empty($post->page_template) ? $post->page_template : false;
            ?>
            <p><strong><?php _e('Template') ?></strong><?php
                /**
                 * Fires immediately after the heading inside the 'Template' section
                 * of the 'Page Attributes' meta box.
                 *
                 * @since 4.4.0
                 *
                 * @param string  $template The template used for the current post.
                 * @param WP_Post $post     The current post.
                 */
                do_action('page_attributes_meta_box_template', $template, $post);
                ?></p>
            <label class="screen-reader-text" for="page_template"><?php _e('Page Template') ?></label><select name="page_template" id="page_template">
                <?php
                /**
                 * Filter the title of the default page template displayed in the drop-down.
                 *
                 * @since 4.1.0
                 *
                 * @param string $label   The display value for the default page template title.
                 * @param string $context Where the option label is displayed. Possible values
                 *                        include 'meta-box' or 'quick-edit'.
                 */
                $default_title = apply_filters('default_page_template_title', __('Default Template'), 'meta-box');
                ?>
                <option value="default"><?php echo esc_html($default_title); ?></option>
                <?php page_template_dropdown($template); ?>
            </select>
        <?php }
        ?>
        <p><strong><?php _e('Order') ?></strong></p>
        <p><label class="screen-reader-text" for="menu_order"><?php _e('Order') ?></label><input name="menu_order" type="text" size="4" id="menu_order" value="<?php echo esc_attr($post->menu_order) ?>" /></p>
            <?php if ('page' == $post->post_type && get_current_screen()->get_help_tabs()) { ?>
            <p><?php _e('Need help? Use the Help tab in the upper right of your screen.'); ?></p>
            <?php
        }
    }
}
