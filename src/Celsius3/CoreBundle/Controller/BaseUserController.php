<?php

namespace Celsius3\CoreBundle\Controller;

use Celsius3\CoreBundle\Document\BaseUser;

abstract class BaseUserController extends BaseInstanceDependentController
{

    protected function enableUser(BaseUser $user)
    {
        $user->setEnabled(true);
        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();
    }

    public function baseTransformAction($id, $transformType)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType, array(
            'type' => $this->get('celsius3_core.user_manager')->getCurrentRole($document),
            'instances' => $document->getAdministeredInstances(),
        ));

        return array(
            'document' => $document,
            'transform_form' => $transformForm->createView(),
            'route' => null
        );
    }

    protected function baseDoTransformAction($id, $transformType, $route)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType);

        $request = $this->getRequest();

        $transformForm->bind($request);

        if ($transformForm->isValid()) {
            $data = $transformForm->getData();
            $this->get('celsius3_core.user_manager')->transform($data['type'], $document);

            if (array_key_exists('instances', $data)) {
                $document->getAdministeredInstances()->clear();
                foreach ($data['instances'] as $instance) {
                    $document->addAdministeredInstance($instance);
                }
            }

            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The User was successfully transformed.');

            return $this->redirect($this->generateUrl($route . '_transform', array(
                                'id' => $id
            )));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors transforming the User.');

        return array(
            'document' => $document,
            'edit_form' => $transformForm->createView()
        );
    }

    protected function baseEnableAction($id)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $this->enableUser($document);

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

    protected function baseBatchEnable($element_ids)
    {
        $dm = $this->getDocumentManager();
        $users = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->createQueryBuilder()
                ->field('id')
                ->in($element_ids)
                ->getQuery()
                ->execute();

        foreach ($users as $user) {
            $this->enableUser($user);
        }

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

}
