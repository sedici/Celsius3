<?php

namespace Celsius\Celsius3Bundle\Manager;

class UnionManager
{

    private $dm;
    private $references = array(
        'Country' => array(
            'City' => array(
                'country',
            ),
        ),
        'City' => array(
            'Institution' => array(
                'city',
            ),
        ),
        'Institution' => array(
            'BaseUser' => array(
                'institution',
            ),
            'Institution' => array(
                'parent',
            ),
            'Catalog' => array(
                'institution',
            ),
        ),
    );

    public function __construct($dm)
    {
        $this->dm = $dm;
    }

    public function union($name, $main, $elements)
    {
        foreach ($this->references[$name] as $key => $reference)
        {
            $query = $this->dm->getRepository('CelsiusCelsius3Bundle:' . $key)
                    ->createQueryBuilder()
                    ->update();

            foreach ($reference as $field)
            {
                $query = $query->field($field . '.id')->in(array_keys($elements->toArray()))
                                ->field($field . '.id')->set($main->getId());
            }

            $query->getQuery(array('multiple' => true))
                    ->execute();
        }

        $this->dm->getRepository('CelsiusCelsius3Bundle:' . $name)
                ->createQueryBuilder()
                ->remove()
                ->field('id')->in(array_keys($elements->toArray()))
                ->getQuery()
                ->execute();

        $main->setInstance(null);
        $this->dm->persist($main);
        $this->dm->flush();
    }

}