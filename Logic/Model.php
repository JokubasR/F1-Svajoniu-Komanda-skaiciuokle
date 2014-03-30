<?php
/**
 * @author   Jokūbas Ramanauskas
 * @since    3/30/14
 */

namespace Logic;


class Model
{
    /** @var \Logic\Settings */
    private $_settings;

    private $xPathStages = "//table[@class='raceResults']/tr/td[1]/a";

    private $xPathTeam   = "//div[@id='contentMain']//div[@class='indexContainer']";

    private $xPathResults = "//table[@class='raceResults']/tr[position() > 1]";

    public function __construct()
    {
        $this->_settings = new Settings();
    }

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

    public function getDrivers()
    {
        $data = $this->getContent(Settings::URL_TEAMS_DRIVERS);

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
     * @param $url
     *
     * @return string
     */
    private function getContent($url)
    {
        return file_get_contents($url);
    }


} 