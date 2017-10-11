<?php

namespace Mtt\BlogBundle;

use Mtt\BlogBundle\Cron\CronCompilerPass;
use Mtt\BlogBundle\Telegram\TelegramCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MttBlogBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CronCompilerPass());
        $container->addCompilerPass(new TelegramCompilerPass());
    }
}
