<?php
/**
 * @author   Jokūbas Ramanauskas
 * @since    3/30/14
 */

namespace Logic;


class DataModel
{
    /** @var \Logic\Settings */
    private $_settings;

    private $xPathStages    = "//table[@class='raceResults']/tr/td[1]/a";

    private $xPathTeam      = "//div[@id='contentMain']//div[@class='indexContainer']";

    private $xPathResults   = "//table[@class='raceResults']/tr[position() > 1]";

    private $xPathDrivers   = "//ul[@class='driverMugShot']/li/div/p/a";

    private $teamEngines    = [
        'Mercedes' => [
            'Mercedes',
            'McLaren',
            'Williams',
            'Force India',
        ],
        'Renault' => [
            'Red Bull Racing',
            'Lotus',
            'Toro Rosso',
            'Caterham',
        ],
        'Ferrari' => [
            'Ferrari',
            'Sauber',
            'Marussia',
        ],
    ];

    public function __construct()
    {
        $this->_settings = new Settings();
    }

    /**
     * @return array
     */
    public function getGrandPrixs()
    {
        $data = $this->getContent(Settings::URL_STAGES);

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($data);

        $xpath = new \DOMXPath($doc);

        $items = $xpath->query($this->xPathStages);

        $result = [];
        for ($i = 0; $i < $items->length; $i++) {
            $item = $items->item($i);
            $result[$item->nodeValue] = [
                'link'  => $item->attributes->getNamedItem('href')->nodeValue,
                'title' => $item->nodeValue,
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getTeams()
    {
        $data = $this->getContent(Settings::URL_TEAMS);

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($data);

        $xpath = new \DOMXPath($doc);

        $items = $xpath->query($this->xPathTeam);

        $result = [];

        for ($i = 0; $i < $items->length; $i++) {
            $item     = $items->item($i)->childNodes->item(1);
            $team     = $items->item($i)->childNodes->item(3);

            $result[] = [
                'title' => $item->childNodes->item(1)->attributes->getNamedItem('alt')->nodeValue,
                'image' => $team->childNodes->item(1)->childNodes->item(1)->attributes->getNamedItem('src')->nodeValue,
                'members' => [
                    [
                        'title' => $item->childNodes->item(2)->attributes->getNamedItem('alt')->nodeValue,
                        'image' => $item->childNodes->item(2)->attributes->getNamedItem('src')->nodeValue,
                    ],
                    [
                        'title' => $item->childNodes->item(3)->attributes->getNamedItem('alt')->nodeValue,
                        'image' => $item->childNodes->item(3)->attributes->getNamedItem('src')->nodeValue,
                    ]
                ],
            ];
        }

        return $result;
    }

    /**
     * @param       $stage
     * @param array $stages
     *
     * @return array
     */
    public function getResults($stage, $stages = [])
    {
        if (empty($stages)) {
            $stages = $this->getGrandPrixs();
        }

        if ($stageUrl = $stages[$stage]['link']) {
            $data = $this->getContent("http://www.formula1.com/". $stageUrl);

            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($data);

            $xpath = new \DOMXPath($doc);

            $items = $xpath->query($this->xPathResults);

            $result = [];

            for ($i = 0; $i < $items->length; $i++) {
                $item = $items->item($i);

                $driverId = $item->childNodes->item(2)->nodeValue;

                $result[$driverId] = [
                    'driverId'  => $driverId,
                    'position'  => $item->childNodes->item(0)->nodeValue,
                    'title'     => $item->childNodes->item(4)->firstChild->nodeValue,
                    'points'    => $item->childNodes->item(12)->nodeValue,
                ];
            }

            return $result;
        }
    }

    /**
     * @return array
     */
    public function getDrivers()
    {
        $data = $this->getContent(Settings::URL_DRIVERS);

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($data);

        $xpath = new \DOMXPath($doc);

        $items = $xpath->query($this->xPathDrivers);

        $result = [];

        for ($i = 0; $i < $items->length; $i++) {
            $item = $items->item($i);

            $driverData = $item->childNodes->item(0)->nodeValue;

            $team = $item->childNodes->item(1)->nodeValue;

            $driverData = explode(' ', trim($driverData));

            $driverId = array_shift($driverData);
            $result[$team][] = [
                'driverId'  => $driverId,
                'title'     => implode(' ', $driverData),
                'team'      => $team,
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getEngines()
    {
        return $this->teamEngines;
    }


    /**
     * @param $url
     *
     * @return string
     */
    private function getContent($url)
    {
        return file_get_contents($url);
    }


} 