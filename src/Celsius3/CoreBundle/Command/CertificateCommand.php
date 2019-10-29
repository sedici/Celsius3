<?php

namespace Celsius3\CoreBundle\Command;

use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CertificateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('celsius3:certificate:update')
            ->setDescription('Update certificates for enabled instances.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $output->writeln(shell_exec('acmephp register soporte.celsius@prebi.unlp.edu.ar'));

        # Se toma la ip actual del servidor
        $serverIp = getHostByName(getHostName());

        # Se toman dominios de instancias activas
        /** @var DataRequest $dr */
        $domains = $em->getRepository(Instance::class)->findAllEnabledAndVisibleDomains();

        # Se descartan aquellos que no apunten a nuestro servidor
        $validDomains = array();
        foreach ($domains as $domain) {
            if (getHostByName($domain['host']) === $serverIp) {
                $validDomains[] = $domain['host'];
            }
        }

        # Se authorizan y prueban los dominios
        $output->writeln(shell_exec('acmephp authorize ' . implode(" ", $validDomains)));
        $output->writeln(shell_exec('acmephp check ' . implode(" ", $validDomains)));

        # Se solicita el certificado
        echo implode(" -a ", $validDomains);
        $output->writeln(shell_exec('acmephp request ' . implode(" -a ", $validDomains)));
    }

}
