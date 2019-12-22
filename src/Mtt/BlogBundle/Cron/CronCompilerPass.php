<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:23
 */

namespace Mtt\BlogBundle\Cron;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CronCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('Mtt\BlogBundle\Cron\CronChain')) {
            return;
        }

        $definition = $container->findDefinition('Mtt\BlogBundle\Cron\CronChain');

        $taggedServices = $container->findTaggedServiceIds('cron-daily');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addCronDailyService',
                [new Reference($id)]
            );
        }
    }
}
