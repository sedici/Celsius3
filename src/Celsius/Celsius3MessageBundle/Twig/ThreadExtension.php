<?php

namespace Celsius\Celsius3MessageBundle\Twig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\MessageBundle\Model\ThreadInterface;

class ThreadExtension extends \Twig_Extension
{

    private $container;
    private $environment;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
                'form_to_thread' => new \Twig_Function_Method($this,
                        'formToThread'),
                'get_unread_messages' => new \Twig_Function_Method($this,
                        'getUnreadMessages'),);
    }

    public function formToThread(ThreadInterface $thread)
    {
        return $this->container->get('fos_message.reply_form.factory')
                ->create($thread)->createView();
    }

    public function getUnreadMessages(ThreadInterface $thread)
    {
        $participantProvider = $this->container
                ->get('fos_message.participant_provider');

        $count = 0;
        foreach ($thread->getMessages() as $message) {
            if (!$message
                    ->isReadByParticipant(
                            $participantProvider->getAuthenticatedParticipant())) {
                $count++;
            }
        }

        return $count;
    }

    public function getName()
    {
        return 'thread_extension';
    }

}
