<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\MailTemplate;
use Celsius3\CoreBundle\Form\Type\MailTemplateType;
use Celsius3\CoreBundle\Filter\Type\MailTemplateFilterType;

/**
 * Order controller.
 *
 * @Route("/superadmin/mail")
 */
class SuperadminMailController extends BaseController
{

    protected function listQuery($name)
    {
        //Se obtienen los templetes del directorio.
        $qb = $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getDirectory()->getId());
        return $qb;
    }

    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="superadmin_mails")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('MailTemplate', $this->createForm(new MailTemplateFilterType()));
    }

    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="superadmin_mails_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('MailTemplate', new MailTemplate(), new MailTemplateType($this->getDirectory()));
    }

    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="superadmin_mails_edit")
     * @Template()
     *
     * @param string $id The mail template ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('MailTemplate', $id, new MailTemplateType($this->getDirectory()));
    }

    /**
     * Creates a new Mail Document.
     *
     * @Route("/create", name="superadmin_mails_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminMail:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this
                        ->baseCreate('MailTemplate', new MailTemplate(), new MailTemplateType($this->getDirectory()), 'superadmin_mails');
    }

    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="superadmin_mails_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminMail:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this
                        ->baseUpdate('MailTemplate', $id, new MailTemplateType($this->getDirectory()), 'superadmin_mails');
    }

    /**
     * Deletes a Mails Template
     *
     * @Route("/{id}/delete", name="superadmin_mails_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('MailTemplate', $id, 'superadmin_mails');
    }

    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="superadmin_mails_change_state")
     *
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function changeStateAction($id)
    {
        $template = $this->findQuery('MailTemplate', $id);

        if (!$template) {
            throw $this->createNotFoundException('Unable to find template.');
        }

        $template->setEnabled(!$template->getEnabled());

        $dm = $this->getDocumentManager();
        $dm->persist($template);
        $dm->flush();

        $this->get('session')->getFlashBag()
                ->add('success', 'The Template was successfully '
                        . (($template->getEnabled()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl('superadmin_mails'));
    }

}
