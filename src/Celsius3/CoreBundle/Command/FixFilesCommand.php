<?php

namespace Celsius3\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixFilesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3:fix-files')
            ->setDescription('Actualiza los archivos para corregir el flag enabled.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conn = $this->getContainer()->get('doctrine.dbal.default_connection');

        $limit = 1000;
        $offset = 0;

        $file_count = $em->getRepository('Celsius3CoreBundle:File')
            ->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();

        while ($offset < $file_count) {
            $files = $em->getRepository('Celsius3CoreBundle:File')
                ->createQueryBuilder('f')
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getQuery()
                ->execute();

            $sql = 'SELECT m.tuple FROM metadata m WHERE m.table LIKE :entity AND m.entityId = :id';
            foreach ($files as $file) {
                echo 'Updating file ' . $file->getId() . "\n";
                $query = $conn->prepare($sql);
                $id = $file->getId();
                $entity = 'archivos_pedidos';
                $query->bindParam('id', $id);
                $query->bindParam('entity', $entity, \PDO::PARAM_STR);
                $query->execute();

                $t = $query->fetch();
                $data = unserialize(base64_decode($t['tuple']));
                if ($data && array_key_exists('borrado', $data)) {
                    $file->setEnabled(!((bool) $data['borrado']));
                    $em->persist($file);
                }
                unset($query, $t, $data, $file);
            }
            $em->flush();
            $em->clear();

            unset($sql, $files);

            $offset += $limit;
        }
    }
}
