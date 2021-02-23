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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Controller\Admin\DataRequest;

use Celsius3\CoreBundle\Controller\BaseInstanceDependentController;
use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\OrdersDataRequest;
use Celsius3\CoreBundle\Form\Type\OrdersDataRequestType;
use Celsius3\CoreBundle\Manager\Alert;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;

final class NewOrdersDataRequestViewController extends BaseInstanceDependentController
{
    private $dataRequestRepository;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     * })
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->dataRequestRepository = $entityManager->getRepository(DataRequest::class);
    }

    public function __invoke(Request $request)
    {
        $data_request = $this->createDataRequest($request->request->get('data_request'));

        $form = $this->createForm(OrdersDataRequestType::class, $data_request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataRequestRepository->save($data_request);

            Alert::add(
                Alert::SUCCESS,
                $this->get('translator')->trans('The data request was successfully registered', [], 'Flashes')
            );

            return $this->redirectToRoute('administration');
        }

        return $this->render(
            'Celsius3CoreBundle:Administration:request_data.html.twig',
            ['form' => $form->createView()]
        );
    }

    private function createDataRequest(?array $dr): DataRequest
    {
        $data_request = new OrdersDataRequest($this->getInstance());

        $data = null;
        if ($dr) {
            foreach ($dr as $k => $v) {
                if ($v === '1') {
                    $data[] = $k;
                } elseif (is_array($v) && !empty($v)) {
                    $data[] = [$k => $v];
                }
            }
        }

        if ($data) {
            $data_request->setData(serialize($data));
        }

        return $data_request;
    }
}
