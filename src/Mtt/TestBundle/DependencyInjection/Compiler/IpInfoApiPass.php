<?php

namespace Mtt\TestBundle\DependencyInjection\Compiler;

use App\Service\IpInfo\IpInfoClientInterface;
use Mtt\TestBundle\Service\IpInfo\IpInfoDummyClient;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IpInfoApiPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has(IpInfoClientInterface::class)) {
            $container->setDefinition(
                IpInfoClientInterface::class,
                $container->findDefinition(IpInfoDummyClient::class)
            );
        }
    }
}
