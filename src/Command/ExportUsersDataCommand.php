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

namespace Celsius3\Command;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\DataRequest;
use Celsius3\Entity\UsersDataRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

use function array_key_exists;

final class ExportUsersDataCommand extends Command
{
    private $entityManager;
    private $dataRequestDirectory;

    public function __construct(EntityManagerInterface $entityManager, string $dataRequestDirectory)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->dataRequestDirectory = $dataRequestDirectory;
    }

    protected function configure(): void
    {
        $this->setName('celsius3:export:users-data-requests')
            ->setDescription('Exporta los datos de usuarios dada una solicitud.')
            ->addArgument('users-data-request-id', InputArgument::REQUIRED, 'Users data request identifier');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DataRequest $dataRequest */
        $dataRequest = $this->entityManager->find(
            UsersDataRequest::class,
            (int)$input->getArgument('users-data-request-id')
        );
        $filename = $dataRequest->getInstance()->getAbbreviation() . '_' . str_replace(
            ' ',
            '_',
            $dataRequest->getName()
        ) . '_' . $dataRequest->getStartDate()->format('Ymd') . '_' . $dataRequest->getEndDate()->format(
            'Ymd'
        ) . '.csv';
        $directory = $this->dataRequestDirectory;

        $queryBuilder = $this->entityManager->getRepository(BaseUser::class)->createQueryBuilder('u');
        $queryBuilder->select('u.id');

        foreach ($dataRequest->getArrayData() as $data) {
            $this->add($data, $queryBuilder);
        }

        $this->whereInstance($queryBuilder, $dataRequest);
        $this->whereDates($queryBuilder, $dataRequest);

        $qb2 = clone($queryBuilder);
        $qb2->setMaxResults(1);
        $keys = array_keys($qb2->getQuery()->getArrayResult()[0]);

        $handle = fopen($directory . $filename, 'w+');
        fputcsv($handle, $keys, ';', '"', '\\');

        $results = $queryBuilder->getQuery()->iterate();
        while (($row = $results->next()) !== false) {
            $arr = current($row);

            if (array_key_exists('birthdate', $arr) && $arr['birthdate'] !== null) {
                $arr['birthdate'] = $arr['birthdate']->format('Y-m-d');
            }

            if (array_key_exists('lastLogin', $arr) && $arr['lastLogin'] !== null) {
                $arr['lastLogin'] = $arr['lastLogin']->format('Y-m-d');
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

        $dataRequest->setFile($zipFilename);
        $dataRequest->setVisible(true);
        $this->entityManager->persist($dataRequest);
        $this->entityManager->flush();
    }

    private function add($value, $queryBuilder)
    {
        $function = 'add' . str_replace('_', '', ucwords($value, '_'));
        $this->$function($queryBuilder);
    }

    private function whereInstance($queryBuilder, $dataRequest)
    {
        $queryBuilder->where('u.instance = :instance_id')
            ->setParameter('instance_id', $dataRequest->getInstance()->getId());
    }

    private function whereDates($queryBuilder, $dataRequest)
    {
        $queryBuilder->andWhere('u.createdAt > :startDate')
            ->andWhere('u.createdAt < :endDate')
            ->setParameter('startDate', $dataRequest->getStartDate())
            ->setParameter('endDate', $dataRequest->getEndDate());
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

    private function addLastLogin($qb)
    {
        $qb->addSelect('u.lastLogin');
    }
}
