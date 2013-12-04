<?php

namespace Celsius3\CoreBundle\Helper;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\CustomUserValue;
use Celsius3\CoreBundle\Document\Instance;

class CustomFieldHelper
{

    private $request_stack;
    private $dm;

    public function __construct(RequestStack $request_stack, DocumentManager $dm)
    {
        $this->request_stack = $request_stack;
        $this->dm = $dm;
    }

    public function processCustomFields(Instance $instance, FormInterface $form, BaseUser $document)
    {
        $fields = $this->dm
                ->getRepository('Celsius3CoreBundle:CustomUserField')
                ->findBy(array('instance.id' => $instance->getId()));

        $data = $this->request_stack->getCurrentRequest()->get($form->getName());

        foreach ($fields as $field) {
            if (array_key_exists($field->getKey(), $data)) {
                $value = $this->dm
                        ->getRepository('Celsius3CoreBundle:CustomUserValue')
                        ->findOneBy(array(
                    'field.id' => $field->getId(),
                    'user.id' => $document->getId(),
                ));

                if (!$value) {
                    $value = new CustomUserValue();
                    $value->setField($field);
                    $value->setUser($document);
                }
                $value->setValue($data[$field->getKey()]);
                $this->dm->persist($value);
                $this->dm->flush();
            }
        }
    }

}
