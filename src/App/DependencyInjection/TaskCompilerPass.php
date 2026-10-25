<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 18.10.2026
 * Time: 15:06
 */

namespace App\DependencyInjection;

use App\Worker\TaskChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaskCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(TaskChain::class)) {
            return;
        }

        $definition = $container->findDefinition(TaskChain::class);

        foreach ($container->findTaggedServiceIds('worker-task') as $id => $tags) {
            $definition->addMethodCall(
                'addTaskExecutor',
                [new Reference($id)]
            );
        }
    }
}
