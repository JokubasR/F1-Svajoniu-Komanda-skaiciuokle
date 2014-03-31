<?php
/**
 * @author   Jokūbas Ramanauskas
 * @since    3/30/14
 */


    define('VIEW_DIR', __DIR__ . '/View/');


    function __autoload($class){
        $class = str_replace('\\', '/', $class);
        $filename = __DIR__. '/' . $class . ".php";
        include_once($filename);
    }

    function dd($data){
        die(print_r($data, true));
    }

    /*
     * Router
     */

    $indexController = new Controller\IndexController();

    $query = $_SERVER['REQUEST_URI'];

    if (strpos($query, 'points')) {
        $indexController->pointsAction();
    } else {
        $indexController->defaultAction();
    }

