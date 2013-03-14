<?php

namespace Celsius\Celsius3Bundle\Helper;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Celsius\Celsius3Bundle\Document\BaseUser;
use Celsius\Celsius3Bundle\Document\CustomUserValue;
use Celsius\Celsius3Bundle\Document\Instance;

class CustomFieldHelper
{

    private $request;
    private $dm;

    public function __construct(Request $request, DocumentManager $dm)
    {
        $this->request = $request;
        $this->dm = $dm;
    }

    public function processCustomFields(Instance $instance, FormInterface $form, BaseUser $document)
    {
        $fields = $this->dm->getRepository('CelsiusCelsius3Bundle:CustomUserField')
                ->findBy(array('instance.id' => $instance->getId()));

        $data = $this->request->get($form->getName());

        foreach ($fields as $field)
        {
            if (array_key_exists($field->getKey(), $data))
            {
                $value = $this->dm->getRepository('CelsiusCelsius3Bundle:CustomUserValue')
                        ->findOneBy(array(
                            'field.id' => $field->getId(),
                            'user.id' => $document->getId(),
                        ));

                if (!$value)
                {
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