<?php
/**
 * @author   Jokūbas Ramanauskas
 * @since    3/30/14
 */

namespace Controller;


use Logic\Model;

class IndexController extends BaseController
{
    /** @var \Logic\Model */
    private $_model;

    public function __construct()
    {
        $this->_model = new Model();
    }

    public function defaultAction()
    {
        $stages     = $this->_model->getGrandPrixs();
        $teams      = $this->_model->getDrivers();
        $results    = $this->_model->getResults('Malaysia', $stages);

        return $this->render('index', [
            'stages'    => $stages,
            'teams'     => $teams,
            'results'   => $results,
        ]);
    }
} 