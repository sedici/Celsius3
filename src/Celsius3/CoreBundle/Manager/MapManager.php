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
       /* $ciudades = $this->_em->getConnection()->executeQuery("SELECT c.id, c.dist_ciudad_nombre
            FROM dist_ciudad c
            RIGHT JOIN dist_list d ON c.id = d.dist_ciudad_id
            WHERE d.dist_pcia_id = ?
            GROUP BY c.id
            ORDER BY c.dist_ciudad_nombre ASC
        ", [$provincia]);

        $res = [
            ['id' => '', 'nombre' => 'Ciudad']
        ];
        while($ciudad = $ciudades->fetch()) {
            $res[] = [
                'id' => $ciudad['id'],
                'nombre' => $ciudad['dist_ciudad_nombre']
            ];
        }
        return $res;*/
    }

    /**
     * @param $ciudad
     * @param null $tipo
     * @return array
     */
    public function getResultados(DistCiudad $ciudad, $orden, $tipo = null) {
     /*   $exp = $this->_em->getExpressionBuilder();
        $query = $this->_em->getRepository('AppBundle:DistList')->createQueryBuilder('d')
            ->andWhere('d.distCiudadId = :ciudad')->setParameter('ciudad', $ciudad)
            ->orderBy(sprintf('d.%s', $orden), 'ASC')
        ;
        if (null !== $tipo && $tipo != 'todos') {
            $tipo = $this->_em->getRepository('AppBundle:DistTipos')->findOneByNombre($tipo);
            $query
                ->join('d.tipos', 'tipos')
                ->andWhere('tipos.nombre = :tipo')
                ->setParameter('tipo', $tipo->getNombre())
            ;
        }

//        var_dump($query->getQuery()->getSQL()); die;

        return $query->getQuery()->getResult();*/
    }

    public function search($orden, $nombre = null, $tipo = null)
    {
     /*   $query = $this->_em->getRepository('AppBundle:DistList')->createQueryBuilder('d')
            ->orderBy(sprintf('d.%s', $orden), 'ASC')
        ;
        if (null !== $tipo && $tipo != 'todos') {
            $tipo = $this->_em->getRepository('AppBundle:DistTipos')->findOneByNombre($tipo);
            $query
                ->join('d.tipos', 'tipos')
                ->andWhere('tipos.nombre = :tipo')
                ->setParameter('tipo', $tipo->getNombre())
            ;
        }

        if(null !== $nombre) {
            $query->andWhere('d.alias LIKE :nombre')->setParameter('nombre', '%' . $nombre . '%');
        }

        return $query->getQuery()->getResult();*/
    }

    private function addMarker(Map $map, Instance $instance, $windowOpen = false)
    {

        if (!$instance->getLat() || !$instance->getLong()) {
            return;
        }
        $markerImage = new MarkerImage();

     /*   if ($instance->getTipos()->count() == 2) {
            $markerImage->setUrl('http://fvsa.com/bundles/app/front/img/maps_dist_repuest.png');
        } else if ($instance->getTipos()->count() == 1 && $instance->getTipos()->first()->getNombre() == "Distribuidor") {
            $markerImage->setUrl('http://fvsa.com/bundles/app/front/img/marcador.png');
        } else if ($instance->getTipos()->count() == 1 && $instance->getTipos()->first()->getNombre() == "Repuestero") {
            $markerImage->setUrl('http://fvsa.com/bundles/app/front/img/maps_repuest.png');
        }
*/

        $infoWindow = new InfoWindow();
        $infoWindow->setOpen($windowOpen);
 /*       $infoWindow->setContent(sprintf(
            '<p><h4>%s</h4></p><p>%s, %s - %s</p>', $instance->getAlias(),
            $instance->getDistListDireccion(), $instance->getCiudad(), $instance->s()
        ));
*/
        $marker = new Marker();
        $marker->setIcon($markerImage);
        $marker->setPosition((double) $instance->getLat(), (double)$instance->getLong());
        $marker->setInfoWindow($infoWindow);

        $map->addMarker($marker);
        $map->setCenter((double) $instance->getLat(), (double)$instance->getLong());
    }

    /**
     * @param $instancia
     * @return Map
     */
    public function createMap($instancias, $zoom = 100, $windowOpen = false)
    {
        $map = new Map();//$this->get('ivory_google_map.map');
        $map->setAutoZoom(true);
        $map->setAsync(true);
        /** @var $instance */
        foreach($instancias as $instancia) {
            $this->addMarker($map, $instancia, $windowOpen);
        }
        $map->setStylesheetOptions(array(
            'width'  => '100%',
            'height' => '1000px',
        ));
        //$map->setMapOption('zoom', $zoom);

        return $map;
    }

    /**
     * @param $instancias
     * @param $latitude
     * @param $longitude
     * @return Map
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
