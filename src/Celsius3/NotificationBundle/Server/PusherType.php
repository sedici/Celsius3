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

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\Server;
use React\ZMQ\Context;
use ZMQ;

final class PusherType
{
    private $loop;
    private $host;
    private $port;
    private $zmqHost;
    private $zmqPort;
    private $pusher;

    public function __construct(Pusher $pusher, $host, $port, $zmqHost, $zmqPort)
    {
        $this->pusher = $pusher;
        $this->host = $host;
        $this->port = $port;
        $this->zmqHost = $zmqHost;
        $this->zmqPort = $zmqPort;
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
        /* @var $loop LoopInterface */
        $this->loop = Factory::create();

        $socket = new Server($this->loop);
        $context = new Context($this->loop);

        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->bind('tcp://' . $this->zmqHost . ':' . $this->zmqPort);
        $pull->on('message', [
            $this->pusher,
            'onEntry',
        ]);

        if ($this->host) {
            $socket->listen($this->port, $this->host);
        } else {
            $socket->listen($this->port);
        }

        $server = new IoServer(new HttpServer(new WsServer(new WampServer($this->pusher))), $socket);
    }

    public function getAddress(): string
    {
        return (($this->host) ?: '*') . ':' . $this->port;
    }

    public function getName(): string
    {
        return 'Notification Server';
    }
}
