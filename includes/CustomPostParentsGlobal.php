<?php

class CustomPostParentsGlobal {

    private $cache = array();

    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new CustomPostParentsGlobal();
        }
        return $inst;
    }

    private function __construct() {
        
    }

    /**
     * Generic.
     * @param $view
     * @param array $args
     * @param bool $return
     * @return string
     */
    public function render($view, $args = array(), $return = false) {
        if ($return) {
            ob_start();
        }
        extract($args);

        $file = '/templates/' . $view;
        $found = locate_template('custom-post-parents' . DIRECTORY_SEPARATOR . $file);
        if (!$found) {
            $found = dirname(__DIR__) . $file;
        }
        include $found;
        if ($return) {
            return ob_get_clean();
        }
    }

    public function get_option_schema() {
        $opt = get_option('custom-post-parents-post_types');
        return $opt ? $opt : array();
    }

    public function get_available_post_type_schemas() {
        // Eliminamos _builtin del filtro, nos quedamos con todos los tipos de post que admitan herencia
        $postTypes = get_post_types(array('hierarchical' => true));
        $lookup = array();
        foreach ($postTypes as $postTypeKey => $v) {
            $o = get_post_type_object($postTypeKey);
            $lookup[$postTypeKey] = $o->labels->name;
        }

        return $lookup;
    }

    public function get_post_type_schema($ignoreCache = false) {
        $val = $this->get_option_schema();
        $selectedMap = array();

        foreach ($val as $postType => $values) {
            $selectedMap[$postType]['values'] = (array) $values;
        }
        return $selectedMap;
    }

    public function get_lookup_schema($ignoreCache = false) {

        $postTypes = get_post_types(array('hierarchical' => true));
        $lookup = array();
        foreach ($postTypes as $postTypeKey => $v) {
            $o = get_post_type_object($postTypeKey);
            $lookup[$postTypeKey] = $o->labels->name;
        }

        return $lookup;
    }

}
