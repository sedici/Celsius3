<?php
namespace Celsius3\NotificationBundle\Server;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PusherType
{
    private $app;
    private $server;
    private $loop;
    private $socket;
    private $host;
    private $port;
    private $container;

    public function __construct(ContainerInterface $container, $host, $port)
    {
        $this->container = $container;
        $this->host = $host;
        $this->port = $port;
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
        $this->setupApp();

        /** @var $loop \React\EventLoop\LoopInterface */

        $this->loop = \React\EventLoop\Factory::create();

        $this->socket = new \React\Socket\Server($this->loop);

        $context = new \React\ZMQ\Context($this->loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://' . $this->host . ':5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull
                ->on('message',
                        array(
                                $this->container
                                        ->get(
                                                'celsius3_notification.wamp_server'),
                                'onNotificationEntry'));

        if ($this->host) {
            $this->socket->listen($this->port, $this->host);
        } else {
            $this->socket->listen($this->port);
        }

        $this->server = new \Ratchet\Server\IoServer($this->app, $this->socket,
                $this->loop);
    }

    /**
     * Sets up clank app to bootstrap Ratchet and handle socket requests
     */
    private function setupApp()
    {
        $serverStack = new WampServer(
                $this->container->get('celsius3_notification.wamp_server'));

        $this->app = new WsServer($serverStack);
    }

    public function getAddress()
    {
        return (($this->host) ? $this->host : "*") . ":" . $this->port;
    }

    public function getName()
    {
        return "Notification Server";
    }
}
