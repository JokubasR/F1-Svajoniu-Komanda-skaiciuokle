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

    function d($data){
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }

    function dd($data){
        d($data);
        die();
    }

    /**
     * Translates by keyword
     * @param $message
     *
     * @return mixed
     */
    function translate($message){
        return \Logic\TranslateModel::translate($message);
    }

    /*
     * Router
     */

    $indexController = new Controller\IndexController();

    $query = $_SERVER['REQUEST_URI'];

    try{
        switch(true){
            case strpos($query, 'points'):
                $indexController->pointsAction();
                break;
            case strpos($query, 'best-team'):
                $indexController->bestTeamAction();
                break;
            case strpos($query, 'get-stage-analysis'):
                $indexController->getStageAnalysisAction();
                break;
            case strpos($query, 'stage-analysis'):
                $_active_route = "stage-analysis";
                $indexController->stageAnalysisAction();
                break;
            default:
                $_active_route = "default";
                $indexController->defaultAction();
        }
    } catch (Exception $exception) {
        die('Something went bad!');
    }

    function isMenuKeyActive($route){
        global $_active_route;
        return $route === $_active_route
            ? "class='active'"
            : null;
    }

