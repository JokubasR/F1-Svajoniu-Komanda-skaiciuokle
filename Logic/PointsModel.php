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

    public function calculateQualifyingPoints($data, $raceResult)
    {
        /**@todo implement this */
    }
} 