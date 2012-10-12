<?php

namespace Celsius\Celsius3Bundle\Manager;

class StateManager
{

    private $graph = array(
        'created' => array(
            'events' => array(
                'search' => array(
                    'weight' => 10,
                    'destinationState' => 'searched',
                ),
                'annul' => array(
                    'weight' => 1,
                    'destinationState' => 'annuled',
                ),
            ),
            'previousStates' => array(),
            'originatingEvents' => array(
                'creation'
            ),
        ),
        'searched' => array(
            'events' => array(
                'mirequest' => array(
                    'weight' => 10,
                    'destinationState' => 'requested',
                    'remoteState' => 'created',
                ),
                'sirequest' => array(
                    'weight' => 9,
                    'destinationState' => 'requested',
                ),
                'cancel' => array(
                    'weight' => 2,
                    'destinationState' => 'canceled',
                ),
                'annul' => array(
                    'weight' => 1,
                    'destinationState' => 'annuled',
                ),
            ),
            'previousStates' => array(
                'created',
            ),
            'originatingEvents' => array(
                'search',
            ),
        ),
        'requested' => array(
            'events' => array(
                'receive' => array(
                    'weight' => 10,
                    'destinationState' => 'received',
                ),
                'cancel' => array(
                    'weight' => 2,
                    'destinationState' => 'canceled',
                ),
            ),
            'previousStates' => array(
                'searched',
            ),
            'originatingEvents' => array(
                'mirequest',
                'sirequest',
            ),
        ),
        'received' => array(
            'events' => array(
                'sideliver' => array(
                    'weight' => 10,
                    'destinationState' => 'delivered',
                ),
                'mideliver' => array(
                    'weight' => 9,
                    'destinationState' => 'delivered',
                    'remoteState' => 'received',
                ),
            ),
            'previousStates' => array(
                'requested',
            ),
            'originatingEvents' => array(
                'receive',
                'mideliver',
            ),
        ),
        'delivered' => array(
            'previousStates' => array(
                'received',
            ),
            'originatingEvents' => array(
                'sideliver',
            ),
        ),
        'canceled' => array(
            'previousStates' => array(
                'searched',
                'requested',
            ),
            'originatingEvents' => array(
                'cancel',
            ),
        ),
        'annuled' => array(
            'previousStates' => array(
                'created',
                'searched',
            ),
            'originatingEvents' => array(
                'annul',
            ),
        ),
    );
    
    public function getGraph()
    {
        return $this->graph;
    }

}