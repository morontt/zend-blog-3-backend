<?php

namespace App;

use App\DependencyInjection\CronCompilerPass;
use App\DependencyInjection\Security\Factory\WsseFactory;
use App\DependencyInjection\TelegramCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CronCompilerPass());
        $container->addCompilerPass(new TelegramCompilerPass());

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }
}
