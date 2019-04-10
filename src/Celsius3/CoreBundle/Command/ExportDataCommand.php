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

namespace Celsius3\CoreBundle\Command;

use Celsius3\CoreBundle\Entity\BookType;
use Celsius3\CoreBundle\Entity\CongressType;
use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Entity\State;
use Celsius3\CoreBundle\Entity\ThesisType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportDataCommand extends ContainerAwareCommand
{
    private $materialTypeJoined = false;
    private $journalTypeJoined = false;
    private $bookTypeJoined = false;
    private $thesisTypeJoined = false;
    private $congressTypeJoined = false;
    private $statesJoined = false;
    private $requestJoined = false;

    protected function configure()
    {
        $this->setName('celsius3:export-data')
            ->setDescription('Exporta los datos de una instancia dada una solicitud de datos.')
            ->addArgument('data-request-id', InputArgument::REQUIRED, 'Data request identifier');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var DataRequest $dr */
        $dr = $em->find(DataRequest::class, (int)$input->getArgument('data-request-id'));
        $filename = $dr->getInstance()->getAbbreviation() . '_' . $dr->getName() . '_' . $dr->getStartDate()->format('Ymd') . '_' . $dr->getEndDate()->format('Ymd') . '.csv';
        $directory = $this->getContainer()->getParameter('data_requests_directory');

        $qb = $em->getRepository(Order::class)->createQueryBuilder('o');
        $qb->select('o.code');

        foreach ($dr->getArrayData() as $d) {
            if (is_array($d)) {
                $c = current($d);
                $this->join(key($d), $qb);
                foreach ($c as $v) {
                    $this->add($v, $qb);
                }
            } else {
                $this->add($d, $qb);
            }
        }

        $this->whereInstance($qb, $dr);
        $this->whereDates($qb, $dr);


        $qb2 = clone($qb);
        $qb2->setMaxResults(1);
        $keys = array_keys($qb2->getQuery()->getArrayResult()[0]);

        $handle = fopen($directory . $filename, 'w+');
        fputcsv($handle, $keys);

        $results = $qb->getQuery()->iterate();
        while (false !== ($row = $results->next())) {
            $arr = current($row);
            if (array_key_exists('date', $arr)) {
                $arr['date'] = $arr['date']->format('Y-m-d');
            }

            fputcsv($handle, $arr);
        }
        fclose($handle);

        $dr->setFile($filename);
        $em->persist($dr);
        $em->flush();
    }

    private function whereInstance(&$qb, $dr)
    {
        $this->joinRequest($qb);
        $qb->where('r.instance = :instance_id')
            ->setParameter('instance_id', $dr->getInstance()->getId());
    }

    private function whereDates(&$qb, $dr)
    {
        $qb->andWhere('o.createdAt > :startDate')
            ->andWhere('o.createdAt < :endDate')
            ->setParameter('startDate', $dr->getStartDate())
            ->setParameter('endDate', $dr->getEndDate());
    }

    private function add($v, &$qb)
    {
        $f = 'add' . ucfirst($v);
        $this->$f($qb);
    }

    private function join($d, &$qb)
    {
        $f = 'join' . ucfirst($d);
        $this->$f($qb);
    }

    private function addCreated(&$qb)
    {
        $qb->addSelect("GROUP_CONCAT(IFELSE(s.type = 'created', s.createdAt, NULL)) AS created_date");
    }

    private function addAnnulled(&$qb)
    {
        $qb->addSelect("GROUP_CONCAT(IFELSE(s.type = 'annulled', s.createdAt, NULL)) AS annulled_date");
    }

    private function addCancelled(&$qb)
    {
        $qb->addSelect("GROUP_CONCAT(IFELSE(s.type = 'cancelled', s.createdAt, NULL)) AS cancelled_date");
    }

    private function addSearched(&$qb)
    {
        $qb->addSelect("GROUP_CONCAT(IFELSE(s.type = 'searched', s.createdAt, NULL)) AS searched_date");
    }

    private function addRequested(&$qb)
    {
        $qb->addSelect("GROUP_CONCAT(IFELSE(s.type = 'requested', s.createdAt, NULL)) AS requested_date");
    }

    private function addReceived(&$qb)
    {
        $qb->addSelect("GROUP_CONCAT(IFELSE(s.type = 'received', s.createdAt, NULL)) AS received_date");
    }

    private function addDelivered(&$qb)
    {
        $qb->addSelect("GROUP_CONCAT(IFELSE(s.type = 'delivered', s.createdAt, NULL)) AS delivered_date");
    }

    private function addDate(&$qb)
    {
        $qb->addSelect('o.createdAt date');
    }

    private function addType(&$qb)
    {
        $this->joinRequest($qb);
        $qb->addSelect('r.type');
    }

    private function addMaterialType(&$qb)
    {
        $this->joinMaterialType($qb);
        $qb->addSelect('TYPE(m) AS material_type');
    }

    private function addTitle(&$qb)
    {
        $this->joinMaterialType($qb);
        $qb->addSelect('m.title');
    }

    private function addAuthors(&$qb)
    {
        $this->joinMaterialType($qb);
        $qb->addSelect('m.authors');
    }

    private function addYear(&$qb)
    {
        $this->joinMaterialType($qb);
        $qb->addSelect('m.year');
    }

    private function addStartPage(&$qb)
    {
        $this->joinMaterialType($qb);
        $qb->addSelect('m.startPage');
    }

    private function addEndPage(&$qb)
    {
        $this->joinMaterialType($qb);
        $qb->addSelect('m.endPage');
    }

    private function addVolume(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('jt.volume');
    }

    private function addNumber(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('jt.number');
    }

    private function addIssn(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('j.ISSN');
    }

    private function addIssne(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('j.ISSNE');
    }

    private function addResponsible(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('j.responsible');
    }

    private function addFrecuency(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('j.frecuency');
    }

    private function addAbbreviation(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('j.abbreviation');
    }

    private function addEditor(&$qb)
    {
        $this->joinBook($qb);
        $qb->addSelect('bt.editor');
    }

    private function addChapter(&$qb)
    {
        $this->joinBook($qb);
        $qb->addSelect('bt.chapter');
    }

    private function addIsbn(&$qb)
    {
        $this->joinBook($qb);
        $qb->addSelect('bt.ISBN');
    }

    private function addDirector(&$qb)
    {
        $this->joinThesis($qb);
        $qb->addSelect('tt.director');
    }

    private function addDegree(&$qb)
    {
        $this->joinThesis($qb);
        $qb->addSelect('tt.degree');
    }

    private function addPlace(&$qb)
    {
        $this->joinCongress($qb);
        $qb->addSelect('ct.place');
    }

    private function addCommunication(&$qb)
    {
        $this->joinCongress($qb);
        $qb->addSelect('ct.communication');
    }

    private function addName(&$qb)
    {
        $this->joinJournal($qb);
        $qb->addSelect('COALESCE(jt.other, j.name) name');
    }

    private function joinRequest(&$qb)
    {
        if (!$this->requestJoined) {
            $qb->innerJoin('o.originalRequest', 'r');
            $this->requestJoined = true;
        }
    }

    private function joinMaterialType(&$qb)
    {
        if (!$this->materialTypeJoined) {
            $qb->innerJoin('o.materialData', 'm');
            $this->materialTypeJoined = true;
        }
    }

    private function joinJournal(&$qb)
    {
        $this->joinMaterialType($qb);

        if (!$this->journalTypeJoined) {
            $qb->leftJoin(JournalType::class, 'jt', Join::WITH, 'm = jt');
            $qb->leftJoin('jt.journal', 'j');
            $this->journalTypeJoined = true;
        }
    }

    private function joinBook(&$qb)
    {
        $this->joinMaterialType($qb);

        if (!$this->bookTypeJoined) {
            $qb->leftJoin(BookType::class, 'bt', Join::WITH, 'm = bt');
            $this->bookTypeJoined = true;
        }
    }

    private function joinThesis(&$qb)
    {
        $this->joinMaterialType($qb);

        if (!$this->thesisTypeJoined) {
            $qb->leftJoin(ThesisType::class, 'tt', Join::WITH, 'm = tt');
            $this->thesisTypeJoined = true;
        }
    }

    private function joinCongress(&$qb)
    {
        $this->joinMaterialType($qb);

        if (!$this->congressTypeJoined) {
            $qb->leftJoin(CongressType::class, 'ct', Join::WITH, 'm = ct');
            $this->congressTypeJoined = true;
        }
    }

    private function joinStates(&$qb)
    {
        $this->joinRequest($qb);

        if (!$this->statesJoined) {
            $qb->groupBy('o.id');
            $qb->leftJoin(State::class, 's', Join::WITH, 's.request = r.id');
            $this->statesJoined = true;
        }
    }
}
