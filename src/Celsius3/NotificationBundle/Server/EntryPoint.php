<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\NotificationBundle\Server;

use Symfony\Component\Console\Output\OutputInterface;

class EntryPoint
{
    private $server;
    private $output;

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     *
     */
    public function __construct($server)
    {
        $this->server = $server;
    }

    /**
     * Launches the relevant servers needed by Clank.
     */
    public function launch()
    {
        if (!$this->server) {
            throw new \Exception("Unable to find Server Service.");
        }

        if ($this->getOutput()) {
            $this->getOutput()->writeln("Launching " . $this->server->getName() . " on: " . $this->server->getAddress());
        }
        //launch server into background process?
        $this->server->launch();
    }
}
