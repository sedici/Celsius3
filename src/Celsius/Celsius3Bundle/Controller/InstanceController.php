<?php

namespace Celsius\Celsius3Bundle\Controller;

abstract class InstanceController extends BaseController
{

    protected function baseConfigureAction($id)
    {
        $document = $this->findQuery('Instance', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $configureForm = $this->createFormBuilder();

        foreach ($document->getConfigurations() as $configuration)
        {
            $configureForm->add($configuration->getKey(), $this->get('configuration_helper')->guessConfigurationType($configuration), array(
                'data' => $this->get('configuration_helper')->getCastedValue($configuration),
                'label' => $configuration->getName(),
                'required' => false
            ));
        }

        $configureForm = $configureForm->getForm();

        return array(
            'document' => $document,
            'configure_form' => $configureForm->createView(),
        );
    }

    protected function baseConfigureUpdateAction($id, $route)
    {
        $document = $this->findQuery('Instance', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $configureForm = $this->createFormBuilder();

        foreach ($document->getConfigurations() as $configuration)
        {
            $configureForm->add($configuration->getKey(), $this->get('configuration_helper')->guessConfigurationType($configuration), array(
                'attr' => array(
                    'value' => $configuration->getValue(),
                ),
                'label' => $configuration->getName(),
            ));
        }

        $configureForm = $configureForm->getForm();

        $request = $this->getRequest();

        $configureForm->bind($request);

        if ($configureForm->isValid())
        {
            $dm = $this->getDocumentManager();
            $values = $configureForm->getData();

            foreach ($document->getConfigurations() as $configuration)
            {
                $configuration->setValue($values[$configuration->getKey()]);
                $dm->persist($document);
            }

            $dm->flush();

            $this->addFlash('success', 'The instance was successfully configured.');

            return $this->redirect($this->generateUrl($route . '_configure', array('id' => $id)));
        }

        return array(
            'document' => $document,
            'configure_form' => $configureForm->createView(),
        );
    }

}
