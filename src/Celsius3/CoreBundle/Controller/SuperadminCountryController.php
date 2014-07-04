<?php
namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Country;
use Celsius3\CoreBundle\Form\Type\CountryType;
use Celsius3\CoreBundle\Filter\Type\CountryFilterType;

/**
 * Order controller.
 *
 * @Route("/superadmin/country")
 */
class SuperadminCountryController extends BaseController
{

    /**
     * Lists all Country documents.
     *
     * @Route("/", name="superadmin_country")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Country', $this->createForm(new CountryFilterType()));
    }

    /**
     * Displays a form to create a new Country document.
     *
     * @Route("/new", name="superadmin_country_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Country', new Country(), new CountryType($this->getDirectory()));
    }

    /**
     * Creates a new Country document.
     *
     * @Route("/create", name="superadmin_country_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCountry:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Country', new Country(), new CountryType($this->getDirectory()), 'superadmin_country');
    }

    /**
     * Displays a form to edit an existing Country document.
     *
     * @Route("/{id}/edit", name="superadmin_country_edit")
     * @Template()
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Country', $id, new CountryType($this->getDirectory()));
    }

    /**
     * Edits an existing Country document.
     *
     * @Route("/{id}/update", name="superadmin_country_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCountry:edit.html.twig")
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Country', $id, new CountryType($this->getDirectory()), 'superadmin_country');
    }

    /**
     * Deletes a Country document.
     *
     * @Route("/{id}/delete", name="superadmin_country_delete")
     * @Method("post")
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Country', $id, 'superadmin_country');
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_country_batch")
     *
     * @return array
     */
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:SuperadminCountry:batchUnion.html.twig', $this->baseUnion('Country', $element_ids));
    }

    /**
     * Unifies a group of Country documents.
     *
     * @Route("/doUnion", name="superadmin_country_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        $main_id = $this->getRequest()->request->get('main');

        return $this->baseDoUnion('Country', $element_ids, $main_id, 'superadmin_country');
    }
}
