<?php
/**
 * @author   Jokūbas Ramanauskas
 * @since    3/31/14
 */

namespace Logic;

/**
 * Class PointsModel
 * @package Logic
 */
class PointsModel
{
    /** @var DataModel */
    private $_dataModel;

    public function __construct()
    {
        $this->_dataModel = new DataModel();
    }

    /**
     * Place => points
     * @var array
     */
    private $pointsQualifying = [
        1   => 10,
        2   => 8,
        3   => 6,
        4   => 5,
        5   => 4,
        6   => 3,
        7   => 2,
        8   => 1,
    ];

    /**
     * Place => points
     * @var array
     */
    private $pointsRace = [
        1   => 25,
        2   => 18,
        3   => 15,
        4   => 12,
        5   => 10,
        6   => 8,
        7   => 6,
        8   => 5,
        9   => 4,
        10  => 3,
        11  => 2,
        12  => 2,
    ];

    /**
     * @return array
     */
    public function getQualifyingPoints()
    {
        return $this->pointsQualifying;
    }

    /**
     * @return array
     */
    public function getRacePoints()
    {
        return $this->pointsRace;
    }

    public function calculateQualifyingPoints($data, $stage)
    {
        $qualifyingResults  = $this->_dataModel->getQualifyingResults($data['stage']);
        $raceResults        = $this->_dataModel->getResults(null, null, $data['stage']);

//        var_dump($raceResults);
//        dd($qualifyingResults);

        $points = [];

        $pilots = array_slice($data, 1, 2, true);
        

        foreach ($qualifyingResults as $result) {
            
            foreach ($pilots as $key => $pilot) {
                if ($result['driverId'] === $pilot) {
                    $points['qualifying'][$key] = $this->getQualifyingPoints()[$result['position']];
                }
            }
            
            if ($result['team'] === $data['team']) {
                $points['qualifying']['team'] = $this->getQualifyingPoints()[$result['position']] * 0.8
                                        + $points['qualifying']['team'];
            }

            if ($this->_dataModel->engineHasTeam($data['engine'], $result['team'])) {
                $points['qualifying']['engine'] = $this->getQualifyingPoints()[$result['position']] * 0.2
                                        + $points['qualifying']['engine'];
            }
        }

        foreach ($raceResults as $result) {
            foreach ($pilots as $key => $pilot) {
                if ($result['driverId'] === $pilot) {
                    $points['race'][$key] = $this->getRacePoints()[$result['position']];
                }
            }

            if ($result['team'] === $data['team']) {
                $points['race']['team'] = $this->getQualifyingPoints()[$result['position']] * 0.8
                                        + $points['race']['team'];
            }

            if ($this->_dataModel->engineHasTeam($data['engine'], $result['team'])) {
                $points['race']['engine'] = $this->getQualifyingPoints()[$result['position']] * 0.2
                                        + $points['race']['engine'];
            }
        }


        dd($points);


    }
} 