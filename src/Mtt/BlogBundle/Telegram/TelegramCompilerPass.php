<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 11.10.17
 * Time: 23:27
 */

namespace Mtt\BlogBundle\Telegram;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TelegramCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('mtt_blog.telegram_robot')) {
            return;
        }

        $definition = $container->findDefinition('mtt_blog.telegram_robot');

        foreach ($container->findTaggedServiceIds('telegram-command') as $id => $tags) {
            $definition->addMethodCall(
                'addCommand',
                [new Reference($id)]
            );
        }
    }
}
