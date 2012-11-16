<?php

namespace Celsius\Celsius3Bundle\Controller;

abstract class BaseUserController extends BaseInstanceDependentController
{

    public function baseTransformAction($id, $transformType)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType, array(
            'type' => $this->get('user_manager')->getCurrentRole($document),
                ));

        return array(
            'document' => $document,
            'transform_form' => $transformForm->createView(),
            'route' => null,
        );
    }

    protected function baseDoTransformAction($id, $transformType, $route)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType);

        $request = $this->getRequest();

        $transformForm->bind($request);

        if ($transformForm->isValid())
        {
            $data = $transformForm->getData();
            $document = $this->get('user_manager')->transform($data['type'], $document);
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('session')->getFlashBag()->add('success', 'The User was successfully transformed.');

            return $this->redirect($this->generateUrl($route . '_transform', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error', 'There were errors transforming the User.');

        return array(
            'document' => $document,
            'edit_form' => $transformForm->createView(),
        );
    }

    protected function baseEnableAction($id)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $document->setEnabled(true);
        $dm = $this->getDocumentManager();
        $dm->persist($document);
        $dm->flush();

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

}