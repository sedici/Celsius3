<?php

namespace Celsius3\NotificationBundle\Server;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Wamp\WampServerInterface;

class EntryPoint
{
    private $container;
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
    public function __construct($container, $server)
    {
        $this->container = $container;
        $this->server = $server;
    }

    /**
     * Launches the relevant servers needed by Clank.
     */
    public function launch()
    {
        //$this->server = $this->container->get($this->server);
        if (!$this->server) {
            throw new \Exception("Unable to find Server Service.");
        }

        if ($this->getOutput()) {
            $this->getOutput()
                    ->writeln(
                            "Launching " . $this->server->getName() . " on: "
                                    . $this->server->getAddress());
        }
        //launch server into background process?
        $this->server->launch();
    }
}
