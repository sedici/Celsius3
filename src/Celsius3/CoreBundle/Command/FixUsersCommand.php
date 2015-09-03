<?php

namespace Celsius3\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixUsersCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3:fix-users')
                ->setDescription('Actualiza la forma de entrega de los usuarios de Celsius3');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conn = $this->getContainer()->get('doctrine.dbal.default_connection');
        
        $users = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->findAll();
        
        $sql = 'SELECT m.tuple FROM metadata m WHERE m.table LIKE :entity AND m.entityId = :id';
        foreach ($users as $user) {
            echo 'Updating user ' . $user->getUsername() . "\n";
            $query = $conn->prepare($sql);
            $id = $user->getId();
            $entity = 'usuarios';
            $query->bindParam('id', $id);
            $query->bindParam('entity', $entity, \PDO::PARAM_STR);
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
