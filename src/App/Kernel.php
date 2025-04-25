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

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);

        if (!defined('APP_VAR_DIR')) {
            define('APP_VAR_DIR', dirname(__DIR__, 2) . '/var');
        }
        if (!defined('APP_WEB_DIR')) {
            define('APP_WEB_DIR', dirname(__DIR__, 2) . '/web');
        }
    }

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
