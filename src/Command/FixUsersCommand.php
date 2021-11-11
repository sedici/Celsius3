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

namespace Celsius3\Command;

use Celsius3\CoreBundle\Entity\BaseUser;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixUsersCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(EntityManagerInterface $entityManager, Connection $connection)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->connection = $connection;
    }

    protected function configure()
    {
        $this->setName('celsius3:fix-users')
            ->setDescription('Actualiza la forma de entrega de los usuarios de Celsius3');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->entityManager;
        $conn = $this->connection;

        $users = $em->getRepository(BaseUser::class)
            ->findAll();

        $sql = 'SELECT m.tuple FROM metadata m WHERE m.table LIKE :entity AND m.entityId = :id';
        foreach ($users as $user) {
            echo 'Updating user ' . $user->getUsername() . "\n";
            $query = $conn->prepare($sql);
            $id = $user->getId();
            $entity = 'usuarios';
            $query->bindParam('id', $id);
            $query->bindParam('entity', $entity, PDO::PARAM_STR);
            $query->execute();

            $t = $query->fetch();
            $data = unserialize(base64_decode($t['tuple']));
            if (intval($data['Codigo_FormaEntrega']) === 1) {
                $user->setPdf(false);
                $em->persist($user);
            }
            unset($query, $t, $data, $user);
        }
        $em->flush();
    }
}
