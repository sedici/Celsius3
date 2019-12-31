<?php

namespace Celsius3\CoreBundle\Command;

use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CertificateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('celsius3:certificate:update')
            ->setDescription('Update certificates for enabled instances.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $publicPath = $this->getContainer()->get('kernel')->getRootDir().'/../web';

        # Se toma la ip actual del servidor
        $serverIp = getHostByName('servicio.prebi.unlp.edu.ar');

        # Se toman dominios de instancias activas
        /** @var DataRequest $dr */
        $domains = $em->getRepository(Instance::class)->findAllDomains();
        $directory = $em->getRepository(Instance::class)->findOneBy(['url' => 'directory']);

        # Se descartan aquellos que no apunten a nuestro servidor
        $validDomains = array();
        foreach ($domains as $domain) {
            if (getHostByName($domain['host']) === $serverIp) {
                $validDomains[] = $domain['host'];
            }
        }

        # Solicitud de certificado
        $output->writeln(shell_exec('certbot --non-interactive --agree-tos -m soporte.celsius@prebi.unlp.edu.ar --expand certonly --webroot -w '.$publicPath.' -d ' . implode(" -d ", $validDomains) . ' -d ' . $directory->getHost()));
    }

}
