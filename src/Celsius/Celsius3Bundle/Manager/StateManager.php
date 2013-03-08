<?php

namespace Celsius\Celsius3Bundle\Manager;

use Celsius\Celsius3Bundle\Exception\NotFoundException;

class StateManager
{

    private $class_prefix = 'Celsius\\Celsius3Bundle\\Document\\';
    private $event_classes = array(
        'creation' => 'Creation',
        'search' => 'Search',
        'sirequest' => 'SingleInstanceRequest',
        'mirequest' => 'MultiInstanceRequest',
        'receive' => 'Receive',
        'sideliver' => 'SingleInstanceDeliver',
        'mideliver' => 'MultiInstanceDeliver',
        'cancel' => 'Cancel',
        'annul' => 'Annul',
    );
    private $graph = array(
        'created' => array(
            'positive' => true,
            'mandatory' => true,
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
            'mandatory' => true,
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
            'mandatory' => true,
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
        'approval_pending' => array(
            'positive' => true,
            'mandatory' => false,
            'events' => array(
                'approve' => array(
                    'weight' => 10,
                    'destinationState' => 'received',
                ),
                'reclaim' => array(
                    'weight' => 2,
                    'destinationState' => 'canceled',
                ),
            ),
            'previousStates' => array(
                'requested',
            ),
            'originatingEvents' => array(
                'receive',
            ),
        ),
        'received' => array(
            'positive' => true,
            'mandatory' => true,
            'events' => array(
                'sideliver' => array(
                    'weight' => 10,
                    'destinationState' => 'delivered',
                ),
                'mideliver' => array(
                    'weight' => 9,
                    'destinationState' => 'delivered',
                    'remoteState' => 'approval_pending',
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
            'mandatory' => true,
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
            'mandatory' => false,
            'events' => array(),
            'previousStates' => array(
                'searched',
                'requested',
                'approval_pending',
            ),
            'originatingEvents' => array(
                'cancel',
            ),
        ),
        'annuled' => array(
            'positive' => false,
            'mandatory' => false,
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

    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundException($message, $previous);
    }

    public function getClassNameForEvent($event)
    {
        if (!array_key_exists($event, $this->event_classes))
        {
            throw $this->createNotFoundException('Event not found.');
        }

        return $this->event_classes[$event];
    }

    public function getFullClassNameForEvent($event)
    {
        if (!array_key_exists($event, $this->event_classes))
        {
            throw $this->createNotFoundException('Event not found.');
        }

        return $this->class_prefix . $this->event_classes[$event];
    }
    
    public function getStateForEvent($event)
    {
        if (!array_key_exists($event, $this->event_classes))
        {
            throw $this->createNotFoundException('Event not found.');
        }

        $data = null;

        foreach ($this->graph as $state)
        {
            if ($state['positive'])
            {
                foreach ($state['events'] as $key => $ev)
                {
                    if ($key == $event)
                    {
                        $data = $ev['destinationState'];
                    }
                }
            }
        }
        
        if ($data == null && $event == 'creation')
        {
            $data = 'created';
        }

        return $data;
    }

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
        if (!array_key_exists($state, $this->graph))
        {
            throw $this->createNotFoundException('State not found.');
        }

        return $this->graph[$state];
    }

    public function getEventsToState($state)
    {
        if (!array_key_exists($state, $this->graph))
        {
            throw $this->createNotFoundException('State not found.');
        }

        $data = array();

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

        return $data;
    }

    public function getPreviousPositiveState($state)
    {
        if (!array_key_exists($state, $this->graph))
        {
            throw $this->createNotFoundException('State not found.');
        }

        $data = null;

        if (count($this->graph[$state]['previousStates']) == 0)
        {
            $data = $state;
        } else
        {
            foreach ($this->graph[$state]['previousStates'] as $previous)
            {
                if ($this->graph[$previous]['positive'])
                {
                    $data = $previous;
                }
            }
        }

        return $data;
    }

}