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
        $engines    = $this->_dataModel->getEngines();
        $drivers    = $this->_dataModel->getDrivers();

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
                $bestTeam['stage']  = $this->_dataModel->getGrandPrixTitle($bestTeam['stage']);
            } catch (\Exception $exception) {
                $bestTeam = [];
            }
        }

        die($this->renderAjax('best-team', [
            'bestTeam' => $bestTeam,
        ]));
    }
} 