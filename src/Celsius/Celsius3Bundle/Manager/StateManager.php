<?php

namespace Celsius\Celsius3Bundle\Manager;

class StateManager
{

    private $graph = array(
        'created' => array(
            'events' => array(
                'search' => array(
                    'weight' => 10,
                    'state' => 'searched',
                ),
                'annul' => array(
                    'weight' => 1,
                    'state' => 'annuled',
                ),
            ),
        ),
        'searched' => array(
            'events' => array(
                'mirequest' => array(
                    'weight' => 10,
                    'state' => 'requested',
                ),
                'sirequest' => array(
                    'weight' => 9,
                    'state' => 'requested',
                ),
                'cancel' => array(
                    'weight' => 2,
                    'state' => 'canceled',
                ),
                'annul' => array(
                    'weight' => 1,
                    'state' => 'annuled',
                ),
            ),
        ),
        'requested' => array(
            'events' => array(
                'receive' => array(
                    'weight' => 10,
                    'state' => 'received',
                ),
                'cancel' => array(
                    'weight' => 2,
                    'state' => 'canceled',
                ),
            ),
        ),
        'received' => array(
            'events' => array(
                'deliver' => array(
                    'weight' => 10,
                    'state' => 'delivered',
                ),
            ),
        ),
        'delivered' => array(),
        'canceled' => array(),
        'annuled' => array(),
    );
    
    public function getGraph()
    {
        return $this->graph;
    }

}