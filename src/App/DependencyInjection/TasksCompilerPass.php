<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Service\TaskService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TasksCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(TaskService::class)) {
            return;
        }

        $definition = $container->findDefinition(TaskService::class);

        foreach (array_keys($container->findTaggedServiceIds('console.command')) as $id) {
            if (strpos($id, 'App\\Command\\') !== false) {
                $definition->addMethodCall(
                    'add',
                    [new Reference($id)]
                );
            }
        }
    }
}
