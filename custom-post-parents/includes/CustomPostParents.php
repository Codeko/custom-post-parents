<?php

if (!defined('ABSPATH')) {
    exit; // exit if accessed directly
}

if (!class_exists('CustomPostParents')) {

    class CustomPostParents {
        public static function Instance() {
            static $inst = null;
            if ($inst === null) {
                $inst = new CustomPostParents();
            }
            return $inst;
        }

        function __construct() {
            add_action('pre_get_posts', array(&$this, 'url_check_and_redirect'));
            add_action('init', array(&$this, 'rewrite_perso_posts'));
            add_action('pre_get_posts', array(&$this, 'set_array_cpt'));
            add_filter('parse_query', array(&$this, 'set_array_cpt'));
            add_filter('rewrite_rules_array', array(&$this, 'clear_rewrites'));
            add_filter('register_post_type_args', array(&$this, 'update_post_types'), 10, 2);
        }

        function update_post_types($args, $post_type ){
            $optionSchemas = CustomPostParentsGlobal::instance()->get_post_type_schema();
            if(array_key_exists($post_type, $optionSchemas)){
                $args["rewrite"]["slug"]="/";
            }
            return $args;
        }
        
        function clear_rewrites($rules){
            $optionSchemas = CustomPostParentsGlobal::instance()->get_post_type_schema();
            //Eliminamos los rewrites generados por los post types sobre los que trabajamos
            $regexp="/".join("|", array_keys($optionSchemas))."=/";
            foreach ($rules as $rule => $rewrite) {
                if( preg_match($regexp,$rewrite) ) {
                    unset($rules[$rule]);
                }
            }
            return $rules;
        }
        
        /*
         * Permite encadenar n niveles en la url
         */

        function rewrite_perso_posts() {
            add_rewrite_rule(
                    '.*/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?attachment=$matches[1]&paged=$matches[2]', 'bottom'
            );
            add_rewrite_rule(
                    '.*/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom'
            );
            add_rewrite_rule(
                    '.*/([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', 'bottom'
            );
            add_rewrite_rule(
                    '.*/([^/]+)/?$', 'index.php?attachment=$matches[1]', 'bottom'
            );
            add_rewrite_rule(
                    '(.*)/?$', 'index.php?attachment=$matches[1]', 'bottom'
            );
        }

        /*
         * Incluye todos los tipos de post para la consulta que obtiene el post solicitado (solamente en el frontend)
         */

        function set_array_cpt($query) {            
            if (!is_admin() && $query->is_main_query() && $query->is_singular()) {
                  $query->set('post_type', "any");
            }
            return $query;
        }

        /*
         *  Redirección de urls si varían respecto al permalink correspondiente
         */

        function url_check_and_redirect($query) {
            global $wp;
            if (!is_home() && !is_admin() && $query->is_main_query() && $query->is_singular()) {
                $permalink = get_permalink();
                $url = home_url(add_query_arg(array(), $wp->request)) . '/';
                if ($permalink && strcmp($permalink, $url) != 0) {
                    wp_redirect($permalink, 301);
                }
            }
        }

    }
}
