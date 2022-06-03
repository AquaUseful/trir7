<?php

namespace page\render {

    $PATH_PREFIX = '/var/www/html/php/page/pages/';
    $PATH_SUFFIX = '.php';

    function render_php(string $name): string
    {
        ob_start();
        require(create_path($name));
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    function create_path(string $name): string
    {
        global $PATH_PREFIX, $PATH_SUFFIX;
        return $PATH_PREFIX.$name.$PATH_SUFFIX;
    }
 
}
