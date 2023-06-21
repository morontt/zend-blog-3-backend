<?php

namespace Mtt\TestBundle;

use Mtt\TestBundle\DependencyInjection\Compiler\IpInfoApiPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MttTestBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        if ($container->hasParameter('kernel.environment')
            && 'test' === $container->getParameter('kernel.environment')
        ) {
            $container->addCompilerPass(new IpInfoApiPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
        }
    }
}
