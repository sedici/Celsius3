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

class PusherType
{
    private $loop;
    private $socket;
    private $host;
    private $port;
    private $zmq_host;
    private $zmq_port;
    private $pusher;

    public function __construct(Pusher $pusher, $host, $port, $zmq_host, $zmq_port)
    {
        $this->pusher = $pusher;
        $this->host = $host;
        $this->port = $port;
        $this->zmq_host = $zmq_host;
        $this->zmq_port = $zmq_port;
    }

    public function launch()
    {
        $this->setupServer();

        $this->loop->run();
    }

    /**
     * Sets up loop and server manually to allow periodic timer calls.
     */
    private function setupServer()
    {
        /* @var $loop \React\EventLoop\LoopInterface */
        $this->loop = \React\EventLoop\Factory::create();

        $this->socket = new \React\Socket\Server($this->loop);

        $context = new \React\ZMQ\Context($this->loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://'.$this->zmq_host.':'.$this->zmq_port); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array(
            $this->pusher,
            'onEntry',
        ));

        if ($this->host) {
            $this->socket->listen($this->port, $this->host);
        } else {
            $this->socket->listen($this->port);
        }
        $this->server = new \Ratchet\Server\IoServer(
                    new \Ratchet\Http\HttpServer(
                        new \Ratchet\WebSocket\WsServer(
                            new \Ratchet\Wamp\WampServer(
                                $this->pusher
                            )
                        )
                    ), $this->socket);
    }

    public function getAddress()
    {
        return (($this->host) ? $this->host : '*').':'.$this->port;
    }

    public function getName()
    {
        return 'Notification Server';
    }
}
