<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\MailTemplate;
use Celsius\Celsius3Bundle\Form\Type\MailTemplateType;
use Celsius\Celsius3Bundle\Filter\Type\MailTemplateFilterType;

/**
 * Order controller.
 *
 * @Route("/admin/mail")
 */
class AdminMailController extends BaseInstanceDependentController 
{
    protected function listQuery($name)
    {   
        //Se obtienen los templetes tanto de la instancia como los del directorio.
        // FALTA DIFERENCIAR ENTRE LOS TEMPLATE QUE FUERON MODIFICADOS
         $qb = $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        // ->distinct('code')
                        //field('instance.id')->equals($this->getInstance()->getId());
                        ->field('instance.id')->in(array($this->getInstance()->getId(), null));
                        //->field('state')->equals(1);
        return $qb;        
        
   //  return $qb->addOr($qb->expr()->field('instance.id')->equals(null));
       
       //  $qb = $this->getDocumentManager()
       //                 ->getRepository('CelsiusCelsius3Bundle:' . $name)
       //                 ->createQueryBuilder()
       //                 ->field('instance.id')->equals(null);
       // return $qb;
    }
    
    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->find($id);
    }
 
    
    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="admin_mails")
     * @Template()
     *
     * @return array
     */
    public function indexAction() {

        return $this->baseIndex('MailTemplate', $this->createForm(new MailTemplateFilterType()));
    }
    
    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="admin_mails_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('MailTemplate', new MailTemplate(), new MailTemplateType($this->getInstance()));
    }
    
    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="admin_mails_edit")
     * @Template()
     *
     * @param string $id The mail template ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id) {
        //Se debe determinar si se utilizara admin_mails_edit o admin_mails_create, dependiendo 
        //si la plantilla le pertenece al directorio o a la instancia.
        $template = $this->findQuery('MailTemplate', $id);
        
        if($template->getInstance())
        {
            $route = 'update';
        }
        else
        {
            $route = 'create';
        }

        return $this->baseEdit('MailTemplate', $id, new MailTemplateType($this->getInstance()), $route);
       
    }
    
    /**
     * Creates a new Mail Document.
     *
     * @Route("/create", name="admin_mails_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminMail:new.html.twig")
     *
     * @return array
     */
    public function createAction() {
        return $this->baseCreate('MailTemplate', new MailTemplate(), new MailTemplateType($this->getInstance()), 'admin_mails');
    }
    
    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="admin_mails_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminMail:edit.html.twig")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id) {
        return $this->baseUpdate('MailTemplate', $id, new MailTemplateType($this->getInstance()), 'admin_mails');
    }
    
    /**
     * Deletes a Mails Template
     *
     * @Route("/{id}/delete", name="admin_mails_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id) {
        //Se permitira borrar un template, solo si el mismo le pertence a la instancia
       $template = $this->findQuery('MailTemplate', $id);
       $idInstanceTempalte= $template->getInstance();
  
       if ($idInstanceTempalte == null)
        {
           //El template pertenece al directorio
            throw $this->createNotFoundException('Unable to delete template.');
        }
        else
        {
           return $this->baseDelete('MailTemplate', $id, 'admin_mails');
        }       
      }
    
    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="admin_mails_change_state")
     * 
     * @Template()
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function changeStateAction($id) {
        $template = $this->findQuery('MailTemplate', $id);
        
        if (!$template)
        {
            throw $this->createNotFoundException('Unable to find template.');
        }
        
        $template->setState(!$template->getState());
        
        $dm = $this->getDocumentManager();
        $dm->persist($template);
        $dm->flush();
     
        $this->get('session')->getFlashBag()->add('success', 'The Template was successfully ' .
                (($template->getState()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl('admin_mails'));
    }
}