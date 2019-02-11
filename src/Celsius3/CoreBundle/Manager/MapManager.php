<?php

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Entity\Instance;
use Ivory\GoogleMap\Map;

use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Overlay\Animation;
use Ivory\GoogleMap\Overlay\Icon;
use Ivory\GoogleMap\Overlay\Marker;
use Ivory\GoogleMap\Overlay\MarkerShape;
use Ivory\GoogleMap\Overlay\MarkerShapeType;
use Ivory\GoogleMap\Overlay\Symbol;
use Ivory\GoogleMap\Overlay\SymbolPath;




class MapManager
{
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
        $infoWindow->setContent(sprintf(
            '<p><h4>%s - %s</h4></p><p>%s</p>', $instance->getName(),$instance->getAbbreviation(),
            $instance->getWebsite()
        ));


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
    public function createMap($instancias, $windowOpen = false)
    {      
        
        $map = new Map();
        $map->setAutoZoom(true);
        /*foreach ($instancias as $instancia) {
            $map->getOverlayManager()->addMarker(new Marker(new Coordinate($instancia->getLatitud(), $instancia->getLongitud())));
        }*/
        $map->getOverlayManager()->addMarker(new Marker(new Coordinate(-57.9523734, -34.9189929)));
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
    public function createMapFromApiSearch($instancias, $latitude, $longitude)
    {
        $map = $this->createMap($instancias);
        $map->setHtmlId('map_canvas');
       
        
        return $map;
    }
}
