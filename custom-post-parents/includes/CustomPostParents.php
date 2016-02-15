<?php

if (!defined('ABSPATH')) {
    exit; // exit if accessed directly
}

if (!class_exists('CustomPostParents')) {

    class CustomPostParents {

        private $options;

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
        }

        /*
         * Permite encadenar n niveles en la url
         */

        function rewrite_perso_posts() {
            add_rewrite_rule(
                    '.*/([^/]+)/?$', 'index.php?attachment=$matches[1]', 'top'
            );
            add_rewrite_rule(
                    '(.*)/?$', 'index.php?attachment=$matches[1]', 'top'
            );
        }

        /*
         * Incluye todos los tipos de post para la consulta que obtiene el post solicitado (solamente en el frontend)
         */

        function set_array_cpt($query) {
            global $wp_query;
            if (!is_admin() && $wp_query->is_main_query() && $wp_query->is_singular()) {
                $query->set('post_type', 'any');
            }
            return $query;
        }

        /*
         *  Redirección de urls si varían respecto al permalink correspondiente
         */

        function url_check_and_redirect() {
            global $wp;

            if (!is_home() && !is_admin()) {
                $permalink = get_permalink();
                $url = home_url(add_query_arg(array(), $wp->request)) . '/';
                if (is_single() && strcmp($permalink, $url) != 0) {
                    wp_redirect($permalink, 301);
                }
            }
        }

    }
}
