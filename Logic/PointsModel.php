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
    const TYPE_QUALIFYING   = 'qualifying';
    const TYPE_RACE         = 'race';

    const POINTS_MULTIPLIER_DRIVER  = 1;
    const POINTS_MULTIPLIER_TEAM    = 0.8;
    const POINTS_MULTIPLIER_ENGINE  = 0.2;

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
        12  => 1,
    ];

    /**
     * @param $position
     *
     * @return int
     */
    public function getQualifyingPoints($position)
    {
        return array_key_exists($position, $this->pointsQualifying)
                ? $this->pointsQualifying[$position]
                : 0;
    }

    /**
     * @param $position
     *
     * @return int
     */
    public function getRacePoints($position)
    {
        return array_key_exists($position, $this->pointsRace)
            ? $this->pointsRace[$position]
            : 0;
    }

    /**
     * Returns calculated points for both events
     *
     * @param $data
     *
     * @return array
     */
    public function calculatePoints($data)
    {
        $qualifyingResults  = $this->_dataModel->getQualifyingResults($data['stage']);
        $raceResults        = $this->_dataModel->getResults(null, null, $data['stage']);

        $points = [];

        if (!empty($qualifyingResults)) {
            $points += $this->pointCalculateMacro($qualifyingResults, $data, self::TYPE_QUALIFYING);
        }

        if (!empty($raceResults)) {
            $points += $this->pointCalculateMacro($raceResults, $data, self::TYPE_RACE);
        }

        return $points;
    }

    /**
     * Calculates given type results
     *
     * @param $data
     * @param $team
     * @param $type
     *
     * @return array
     */
    private function pointCalculateMacro($data, $team, $type)
    {
        $pilots = array_slice($team, 1, 2, true);

        $points = [
            $type => [
                'team'      => 0,
                'engine'    => 0,
            ],
        ];

        foreach ($data as $result) {

            foreach ($pilots as $key => $pilot) {
                if ($result['driverId'] === $pilot) {
                    $points[$type][$key] = $this->getPoints($type, $result['position']) * self::POINTS_MULTIPLIER_DRIVER;
                }
            }

            if ($result['team'] === $team['team']) {
                $points[$type]['team'] += $this->getPoints($type, $result['position']) * self::POINTS_MULTIPLIER_TEAM;
            }

            if ($this->_dataModel->getEngineFromResultData($result['team']) === $team['engine']) {
                $points[$type]['engine'] += $this->getPoints($type, $result['position']) * self::POINTS_MULTIPLIER_ENGINE;
            }
        }

        return $points;
    }

    private function getPoints($type, $position)
    {
        switch ($type) {
            case self::TYPE_QUALIFYING:
                return $this->getQualifyingPoints($position);
            break;
            case self::TYPE_RACE:
                return $this->getRacePoints($position);
            break;
        }
    }
} 