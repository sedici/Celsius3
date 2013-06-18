<?php

namespace Celsius3\CoreBundle\Manager;
use Doctrine\ODM\MongoDB\DocumentManager;

class UnionManager
{

    private $dm;

    /**
     * @fixme Deberia modificarse este array y realizar un procesamiento de los
     * documentos para detectar las relaciones de forma automatica
     */
    private $references = array(
            'Country' => array('City' => array('country',),),
            'City' => array('Institution' => array('city',),),
            'Institution' => array('BaseUser' => array('institution',),
                    'Institution' => array('parent',),
                    'Catalog' => array('institution',),),
            'Journal' => array('Order' => array('materialData.journal',),),
            'BaseUser' => array(
                    'Order' => array('owner', 'operator', 'creator',),
                    'BaseUser' => array('librarian',),
                    'Message' => array('sender', 'receiver',),),);

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function union($name, $main, $elements, $updateInstance)
    {
        if (array_key_exists($name, $this->references)) {
            foreach ($this->references[$name] as $key => $reference) {
                foreach ($reference as $field) {
                    $this->dm->getRepository('Celsius3CoreBundle:' . $key)
                            ->createQueryBuilder()->update()
                            ->field($field . '.id')
                            ->in(array_keys($elements->toArray()))
                            ->field($field . '.id')->set($main->getId())
                            ->getQuery(array('multiple' => true))->execute();
                }
            }
        }

        $this->dm->getRepository('Celsius3CoreBundle:' . $name)
                ->createQueryBuilder()->remove()->field('id')
                ->in(array_keys($elements->toArray()))->getQuery()->execute();

        if ($updateInstance) {
            $main->setInstance(null);
            $this->dm->persist($main);
            $this->dm->flush();
        }
    }

}
