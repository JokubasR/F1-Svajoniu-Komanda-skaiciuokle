<?php
/**
 * @author   Jokūbas Ramanauskas
 * @since    3/30/14
 */

namespace Controller;


use Logic\DataModel;
use Logic\PointsModel;

class IndexController extends BaseController
{
    /** @var \Logic\DataModel */
    private $_dataModel;

    /** @var \Logic\PointsModel */
    private $_pointsModel;

    public function __construct()
    {
        $this->_dataModel = new DataModel();
        $this->_pointsModel = new PointsModel();
    }

    public function defaultAction()
    {
        $stages     = $this->_dataModel->getGrandPrixs();
        $teams      = $this->_dataModel->getTeams();
//        $results    = $this->_dataModel->getResults('Malaysia', $stages);
        $engines    = $this->_dataModel->getEngines();
        $drivers    = $this->_dataModel->getDrivers();
//        $qualifyingResult = $this->_dataModel->getQualifyingResults($stages['Malaysia']['link']);

        return $this->render('index', [
            'stages'    => $stages,
            'teams'     => $teams,
            'engines'   => $engines,
            'drivers'   => $drivers,
        ]);
    }

    public function pointsAction()
    {
        var_dump($_POST);
        if (!empty($_POST)) {
            $points = $this->_pointsModel->calculateQualifyingPoints($_POST, null);
        }

        return $this->render('points',[
            'points'    => $points,
        ]);
    }
} 