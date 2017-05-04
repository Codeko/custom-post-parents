<?php

if (!defined('ABSPATH')) {
    exit; // exit if accessed directly
}

if (!class_exists('CustomPostParents')) {

    class CustomPostParents {
        var $post_type=FALSE;

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
            add_action('the_posts', array(&$this, 'prefilter_posts'), 10, 2);
            add_action('the_post', array(&$this, 'reset_cpt'));
        }

        function update_post_types($args, $post_type) {
            $optionSchemas = CustomPostParentsGlobal::instance()->get_post_type_schema();
            if (array_key_exists($post_type, $optionSchemas) && isset($args["rewrite"]) && isset($args["rewrite"]["slug"])) {
                $args["rewrite"]["slug"] = "/";
            }
            return $args;
        }

        function clear_rewrites($rules) {
            //Eliminamos los rewrites generados por los post types sobre los que trabajamos
            //Excepto sobre páginas que tiene los rewrites correctos
            $optionSchemas = CustomPostParentsGlobal::instance()->get_post_type_schema();
            //Quitamos el page ya que afecta al regex al tener todos un parametro de paginado
            //Además page tiene ya los rewrites correctos
            unset($optionSchemas["page"]);
            if (!empty($optionSchemas)) {
                $regexp = "/\\b(" . join("|", array_keys($optionSchemas)) . ")=/";
                foreach ($rules as $rule => $rewrite) {
                    if (preg_match($regexp, $rewrite)) {
                        unset($rules[$rule]);
                    }
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

        function prefilter_posts($posts, $query){
            global $wp;
            if (!is_admin() && $query->is_main_query() && $query->is_singular()) {
                //Revisamos si algún post tiene la url actual
                //Y lo colocamos como primer elemento del listado de posts
                $url= home_url(add_query_arg(array(), $wp->request)) . '/';
                $match_post=FALSE;
                $not_match_posts=array();
                foreach($posts AS $p){
                    $permalink= get_permalink($p);
                    if (!$match_post && $permalink && strcmp($permalink, $url) == 0) {
                        $match_post=$p;
                    }else{
                        $not_match_posts[]=$p;
                    }
                }
                if($match_post){
                    //Aprovechamos para asignar el post type correcto lo antes posible 
                    //Esto da compatibilidad de otros plugins que ejecuten filters
                    $query->set('post_type', $match_post->post_type);
                    array_unshift($not_match_posts, $match_post);
                    $posts=$not_match_posts;
                }
            }
            return $posts;
            
        }
        
        /*
         * Incluye todos los tipos de post para la consulta que obtiene el post solicitado (solamente en el frontend)
         */

        function set_array_cpt($query) {
            if (!is_admin() && $query->is_main_query() && $query->is_singular()) {
                $this->post_type=get_post_type();
                $query->set('post_type', "any");
            }
            return $query;
        }

        /**
         * Volvemos a asignar el post al query para evitar 
         * problemas con otros módulos.
         */
        function reset_cpt() {
            global $wp_query;
            if (!is_admin() && $wp_query->is_main_query() && $wp_query->is_singular()) {
                $wp_query->set('post_type', get_post_type());
            }
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
