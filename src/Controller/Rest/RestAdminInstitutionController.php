<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\InstitutionController;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\City;
use Celsius3\Entity\Country;
use Celsius3\Entity\Instance;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Celsius3\Entity\Institution;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/rest/v1/admin/institution")
 */
class RestAdminInstitutionController extends InstitutionController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
    }


    /**
     * @Get("/interchange", name="rest_admin_institution_interchange", options={"expose"=true})
     */
    public function getInteractionInstitutions(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $country_id = $request->query->get('country_id');
        // $instance = $this->entityManager->getRepository(Instance::class)->find(2); // UNLP
        // throw new \Exception((string) var_dump($instance));
        $institutions = $this->repository
            ->findForInstanceAndGlobal(
                $this->instance, $this->directory, true,
                $this->instance->getHive(), $country_id, $request->query->get('filter') ?? null
            );
        return $this->restRenderer->render(
            $institutions, serializerGroups: 'administration_order_show'
        );
    }


    /**
     * @Get("/parent/{parent_id}", name="rest_admin_institution_parent", options={"expose"=true})
     */
    public function getInstitutionByParent(string $parent_id): Response
    {
        return $this->restRenderer->render(
            $this->repository->findBy([ 'parent' => $parent_id ]),
            serializerGroups: 'administration_order_show'
        );
    }


    /**
     * @Get("/{id}", name="rest_admin_institution_get", options={"expose"=true})
     */
    public function getInstitution(string $id): Response
    { return $this->restRenderer->show($id, 'institution_show'); }


    /**
     * @Get("/{id}/users", name="rest_admin_institution_users", options={"expose"=true})
     */
    public function getInstitutionUsers(string $id): Response
    {
        $users = $this->entityManager
            ->getRepository(BaseUser::class)
            ->getInstitutionUsers($id);
        return $this->restRenderer->render($users, serializerGroups: 'administration_user_show');
    }


    /**
     * @Get("/location/{country_id}/{city_id}", defaults={"city_id" = null}, name="rest_admin_institution", options={"expose"=true})
     */
    public function getInstitutions(string $country_id, ?string $city_id): Response
    {
        $institutions = $this->repository
            ->findForInstanceAndGlobal(
                $this->instance, $this->directory, true,
                $this->instance->getHive(), $country_id, $city_id
            )->getQuery()->getResult();
        return $this->restRenderer->render(
            $institutions, serializerGroups: 'administration_order_show'
        );
    }


    /**
     * @Post("/create", name="rest_admin_institution_create", options={"expose"=true})
     */
    public function createInstitution(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $reqArgs = $request->toArray();

        $em = $this->entityManager;
        $countryRepository = $this->entityManager->getRepository(Country::class);
        $cityRepository = $this->entityManager->getRepository(City::class);
        $instanceRepository = $this->entityManager->getRepository(Instance::class);

        $institution = new Institution();

        $institution->setName(
            $this->checkArg($reqArgs, 'name', isRest: true)
        );

        $institution->setAbbreviation(
            $this->checkArg($reqArgs, 'abbreviation', isRest: true)
        );

        $institution->setWebsite(
            $this->checkArg($reqArgs, 'website', isRest: true)
        );

        $institution->setAddress(
            $this->checkArg($reqArgs, 'address', isRest: true)
        );

        $institution->setCountry($countryRepository->find(
            $this->checkArg($reqArgs, 'country', isRest: true)
        ));

        $cityId = $reqArgs['city'] ?? null;
        if ($cityId !== null) $institution->setCity($cityRepository->find($cityId));

        $parentId = $reqArgs['institution'] ?? null;
        if ($parentId !== null) $institution->setParent($this->repository->find($parentId));

        $institution->setInstance(
            $instanceRepository->find($reqArgs['country']) ?? $this->instance
        );

        $errors = $this->validator->validate($institution);

        if (count($errors) > 0)
            return $this->restRenderer->render(
                [ 'hasErrors' => true, 'errors' => $errors ]
            );

        $this->persistEntity($institution);
        return $this->restRenderer->render($institution, serializerGroups: 'institution_show');
    }
}
