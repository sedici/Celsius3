<?php

namespace Celsius\Celsius3Bundle\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\NormalizerFormatter;

/**
 * Logs to a MongoDB database (Symfony2 Wrapper).
 */
class MongoDBWrapperHandler extends AbstractProcessingHandler {

    private $handler;
    private $container;

    public function __construct(ContainerInterface $container, $level = Logger::INFO, $bubble = true) {
        $this->container = $container;
        $this->handler = new MongoDBHandler(
                        new \Mongo('mongodb://' . $this->container->getParameter('mongodb_host') . ':' . $this->container->getParameter('mongodb_port')),
                        $this->container->getParameter('mongodb_database'),
                        $this->container->getParameter('mongodb_log_collection'),
                        $level,
                        $bubble
        );

        parent::__construct($level, $bubble);
    }

    protected function write(array $record) {
        $this->handler->write($record);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter() {
        return new NormalizerFormatter();
    }

}
