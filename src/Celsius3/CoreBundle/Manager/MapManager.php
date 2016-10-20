<?php

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\InfoWindow;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\MarkerImage;

class MapManager
{
    private $_em;

    public function __construct(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
    }

    public function getCiudades($provincia)
    {

    }

    /**
     * @param $ciudad
     * @param null $tipo
     *
     * @return array
     */
    public function getResultados($ciudad, $orden, $tipo = null)
    {

    }

    public function search($orden, $nombre = null, $tipo = null)
    {

    }

    private function addMarker(Map $map, Instance $instance, $windowOpen = false)
    {

        if (!$instance->getLatitud() || !$instance->getLongitud()) {
            return;
        }

        $markerImage = new MarkerImage();
        $infoWindow = new InfoWindow();
        $infoWindow->setOpen($windowOpen);

        $marker = new Marker();
        $marker->setIcon($markerImage);
        $marker->setPosition((float) $instance->getLatitud(), (float) $instance->getLongitud());
        $marker->setInfoWindow($infoWindow);
        $map->addMarker($marker);
        $map->setCenter((float) $instance->getLatitud(), (float) $instance->getLongitud());
    }

    /**
     * @param $instancia
     *
     * @return Map
     */
    public function createMap($instancias, $zoom = 100, $windowOpen = false)
    {
        $map = new Map();
        $map->setAutoZoom(true);
        $map->setAsync(true);
        /* @var $instance */
        foreach ($instancias as $instancia) {
            $this->addMarker($map, $instancia, $windowOpen);
        }
        $map->setStylesheetOptions(array(
            'width' => '100%',
            'height' => '1000px',
        ));

        return $map;
    }

    /**
     * @param $instancias
     * @param $latitude
     * @param $longitude
     *
     * @return Map
     *
     * @throws \Ivory\GoogleMap\Exception\MapException
     * @throws \Ivory\GoogleMap\Exception\OverlayException
     */
    public function createMapFromApiSearch($instancias, $latitude, $longitude, $autozoom = true)
    {
        $map = $this->createMap($instancias, -1);
        // my position
        $myPosition = new Marker();
        $myPosition->setPosition($latitude, $longitude);
        $map->addMarker($myPosition);

        return $map;
    }
}
