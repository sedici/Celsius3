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

namespace Celsius3\CoreBundle\Command;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\UsersDataRequest;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

final class ExportUsersDataCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('celsius3:export:users-data-requests')
            ->setDescription('Exporta los datos de usuarios dada una solicitud.')
            ->addArgument('users-data-request-id', InputArgument::REQUIRED, 'Users data request identifier');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var DataRequest $dr */
        $dr = $em->find(UsersDataRequest::class, (int)$input->getArgument('users-data-request-id'));
        $filename = $dr->getInstance()->getAbbreviation() . '_' . str_replace(' ', '_', $dr->getName()) . '_' . $dr->getStartDate()->format('Ymd') . '_' . $dr->getEndDate()->format('Ymd') . '.csv';
        $directory = $this->getContainer()->getParameter('data_requests_directory');

        $qb = $em->getRepository(BaseUser::class)->createQueryBuilder('u');
        $qb->select('u.id');

        foreach ($dr->getArrayData() as $d) {
            $this->add($d, $qb);
        }

        $this->whereInstance($qb, $dr);
        $this->whereDates($qb, $dr);

        $qb2 = clone($qb);
        $qb2->setMaxResults(1);
        $keys = array_keys($qb2->getQuery()->getArrayResult()[0]);

        $handle = fopen($directory . $filename, 'w+');
        fputcsv($handle, $keys, ';', '"', '\\');

        $results = $qb->getQuery()->iterate();
        while (false !== ($row = $results->next())) {
            $arr = current($row);
            if (array_key_exists('birthdate', $arr) && $arr['birthdate'] !== null) {
                $arr['birthdate'] = $arr['birthdate']->format('Y-m-d');
            }

            fputcsv($handle, $arr, ';', '"', '\\');
        }
        fclose($handle);

        $zip = new ZipArchive();
        $zipFilename = $filename . ".zip";
        if ($zip->open($directory . $zipFilename, ZipArchive::CREATE) !== true) {
            exit("No se puede abrir el archivo $directory$zipFilename\n");
        }
        $zip->addFile($directory . $filename, $filename);
        $zip->close();

        unlink($directory . $filename);

        $dr->setFile($zipFilename);
        $dr->setVisible(true);
        $em->persist($dr);
        $em->flush();
    }

    private function add($v, $qb)
    {
        $f = 'add' . str_replace('_', '', ucwords($v, '_'));
        $this->$f($qb);
    }

    private function whereInstance($qb, $dr)
    {
        $qb->where('u.instance = :instance_id')
            ->setParameter('instance_id', $dr->getInstance()->getId());
    }

    private function whereDates($qb, $dr)
    {
        $qb->andWhere('u.createdAt > :startDate')
            ->andWhere('u.createdAt < :endDate')
            ->setParameter('startDate', $dr->getStartDate())
            ->setParameter('endDate', $dr->getEndDate());
    }

    private function addEmail($qb)
    {
        $qb->addSelect('u.email');
    }

    private function addFirstName($qb)
    {
        $qb->addSelect('u.name');
    }

    private function addSurname($qb)
    {
        $qb->addSelect('u.surname');
    }

    private function addBirthdate($qb)
    {
        $qb->addSelect('u.birthdate');
    }

    private function addAddress($qb)
    {
        $qb->addSelect('u.address');
    }

    private function addDownloadAuth($qb)
    {
        $qb->addSelect('u.downloadAuth');
    }

    private function addPdf($qb)
    {
        $qb->addSelect('u.pdf');
    }

    private function addLocked($qb)
    {
        $qb->addSelect('u.locked');
    }

    private function addInstitution($qb)
    {
    }

    private function addObservaciones($qb)
    {
        $qb->addSelect('u.observaciones');
    }
}
