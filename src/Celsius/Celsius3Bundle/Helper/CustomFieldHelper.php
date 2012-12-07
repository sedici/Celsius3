<?php

namespace Celsius\Celsius3Bundle\Helper;

use Celsius\Celsius3Bundle\Document\CustomUserValue;

class CustomFieldHelper
{

    private $request;
    private $dm;

    public function __construct($request, $dm)
    {
        $this->request = $request;
        $this->dm = $dm;
    }

    public function processCustomFields($instance, $form, $document)
    {
        $fields = $this->dm->getRepository('CelsiusCelsius3Bundle:CustomUserField')
                ->createQueryBuilder()
                ->field('instance.id')->equals($instance->getId())
                ->getQuery()
                ->execute();

        $data = $this->request->get($form->getName());

        foreach ($fields as $field)
        {
            if (array_key_exists($field->getKey(), $data))
            {
                $value = $this->dm->getRepository('CelsiusCelsius3Bundle:CustomUserValue')
                        ->createQueryBuilder()
                        ->field('field.id')->equals($field->getId())
                        ->field('user.id')->equals($document->getId())
                        ->getQuery()
                        ->getSingleResult();

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