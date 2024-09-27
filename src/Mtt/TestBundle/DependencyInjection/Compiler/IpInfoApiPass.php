<?php

namespace Mtt\TestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IpInfoApiPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('App\Service\IpInfo\IpInfoClientInterface')) {
            $container->setDefinition(
                'App\Service\IpInfo\IpInfoClientInterface',
                $container->findDefinition('Mtt\TestBundle\Service\IpInfo\IpInfoDummyClient')
            );
        }
    }
}
