<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\NormalizerFormatter;

/**
 * Logs to a MongoDB database (Symfony2 Wrapper).
 */
class MongoDBWrapperHandler extends AbstractProcessingHandler
{

    private $handler;
    private $container;

    public function __construct(ContainerInterface $container, $level = Logger::NOTICE, $bubble = true)
    {
        $this->container = $container;
        $this->handler = new MongoDBHandler(new \MongoClient('mongodb://' . $this->container->getParameter('mongodb_host') . ':' . $this->container->getParameter('mongodb_port')), $this->container->getParameter('mongodb_database'), $this->container->getParameter('mongodb_log_collection'), $level, $bubble);

        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $this->handler->write($record);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new NormalizerFormatter();
    }

}