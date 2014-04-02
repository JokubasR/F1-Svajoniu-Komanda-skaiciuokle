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
        if (!empty($_POST)) {
            try {
                $points = $this->_pointsModel->calculatePoints($_POST);
            } catch(\Exception $exception) {
                $points = [];
            }
        }

        die( $this->renderAjax('points',[
            'results'    => $points,
        ]));
    }

    public function bestTeamAction()
    {
        if (!empty($_POST)) {
            try {
                $bestTeam = $this->_pointsModel->getBestTeam($_POST['stage']);
            } catch (\Exception $exception) {
                $bestTeam = [];
            }
        }
dd($bestTeam);
        die($this->renderAjax('points', [
            'bestTeam' => $bestTeam,
        ]));
    }
} 