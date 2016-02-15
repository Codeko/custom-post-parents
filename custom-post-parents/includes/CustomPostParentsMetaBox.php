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
            foreach ($optionSchemas as $pageType) {
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
                    'custom-post-parents-metabox', __('Jerarquia', 'custom-post-parents'), array($this, 'add_meta_boxes_add_callback'), $ownerPageType, 'side', 'high', array(
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

        echo "<p><strong>" . __('Padre actual', 'custom-post-parents') . ":</strong>
                <span id='parent_id_status'>
                " . ($post->post_parent ? get_the_title($post->post_parent) : __('(no parent)') ) . "
                </span></p>
                <input type='hidden' id='parent_id' name='parent_id' value='{$post->post_parent}' />";

        echo "<p><strong>". __('Tipo de post').": </strong> 
        <select name='my_meta_box_post_type' id='my_meta_box_post_type'>";
        $post_types = get_post_types('', 'objects');
        foreach ($post_types as $post_type) {
            if ($post_type->hierarchical == true && in_array($post_type->name, $schema["values"]) ) {
                echo "<option value = '";
                echo esc_attr($post_type->name);
                echo "' ";
                $pater = get_post_type($post->post_parent);
                if ($pater === $post_type->name) {
                    echo "selected";
                }
                echo ">";
                echo esc_html($post_type->label);
                echo "</option>";
            }
        }
        echo "</select>";
        echo "</p>";

        foreach ($schema['values'] as $k => $pageType) {
            $pages = wp_dropdown_pages(array(
                'post_type' => $pageType,
                'selected' => $post->post_parent,
                'name' => 'selection-parent-id-' . $pageType,
                'id' => 'selection-parent-id-' . $pageType,
                'show_option_none' => __("Ninguno"),
                'option_none_value' => '0',
                 'hierarchical' => 0,
                'sort_column' => 'menu_order, post_title',
                'echo' => 0)
            );
// Only ONCE:
            if ($isFirst) {
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
            }

            
                $label = $lookup[$pageType];
                echo "<div id='" . $pageType . "-container' class='padres-containers'><p><strong>{$label}: </strong> ";
                if (!empty($pages)) {
                    echo $pages;
                }else{
                    echo __('none');
                }
                echo "</p></div>";
            
            $isFirst = false;
        }
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
        </script><?php
    }

}
