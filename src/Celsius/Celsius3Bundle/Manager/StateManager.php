<?php

namespace Celsius\Celsius3Bundle\Manager;

class StateManager
{

    private $graph = array(
        'created' => array(
            'positive' => true,
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
            'positive' => true,
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
            'positive' => true,
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
            'positive' => true,
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
            'positive' => true,
            'events' => array(),
            'previousStates' => array(
                'received',
            ),
            'originatingEvents' => array(
                'sideliver',
            ),
        ),
        'canceled' => array(
            'positive' => false,
            'events' => array(),
            'previousStates' => array(
                'searched',
                'requested',
            ),
            'originatingEvents' => array(
                'cancel',
            ),
        ),
        'annuled' => array(
            'positive' => false,
            'events' => array(),
            'previousStates' => array(
                'created',
                'searched',
            ),
            'originatingEvents' => array(
                'annul',
            ),
        ),
    );

    public function getPositiveStates()
    {
        return array_filter($this->graph, function($value)
                        {
                            return $value['positive'];
                        }
        );
    }
    
    public function getStateData($state)
    {
        $data = null;
        
        if (array_key_exists($state, $this->graph))
        {
            $data = $this->graph[$state];
        }
        
        return $data;
    }
    
    public function getEventsToState($state)
    {
        $data = array();
        
        if (array_key_exists($state, $this->graph))
        {
            foreach ($this->graph[$state]['previousStates'] as $previous)
            {
                foreach ($this->graph[$previous]['events'] as $key => $event)
                {
                    if ($event['destinationState'] == $state)
                    {
                        $data[$key] = $event;
                    }
                }
            }
        }
        
        return $data;
    }

}