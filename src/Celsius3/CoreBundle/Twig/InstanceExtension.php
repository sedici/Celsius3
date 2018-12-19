<?php

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Entity\MailTemplate;
use Celsius3\CoreBundle\Entity\Template;
use Doctrine\ORM\EntityManager;

class InstanceExtension extends \Twig_Extension
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_instance_url', array($this, 'getInstanceUrl')),
            new \Twig_SimpleFunction('template_edited', array($this, 'templateEdited'))
        );
    }

    public function getInstanceUrl($id)
    {
        $instance = $this->entityManager->getRepository('Celsius3CoreBundle:Instance')->find($id);

        return $instance->getUrl();
    }

    public function templateEdited(MailTemplate $template) {
        if ($template->getInstance() !== null && $template->getInstance()->getUrl() !== 'directory') {
            return true;
        } else {
            $templates = $this->entityManager->getRepository(MailTemplate::class)->templateEdited($template);

            if (count($templates) > 0) {
                return true;
            }

            return false;
        }
    }
}