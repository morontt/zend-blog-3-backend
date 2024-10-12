<?php

namespace App;

use App\DependencyInjection\CronCompilerPass;
use App\DependencyInjection\Security\Factory\WsseFactory;
use App\DependencyInjection\TelegramCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

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

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $container->import($confDir . '/{packages}/*.yaml');
        $container->import($confDir . '/{packages}/' . $this->environment . '/*.yaml');
        if (is_file($confDir . '/services.yaml')) {
            $container->import($confDir . '/services.yaml');
            $container->import($confDir . '/{services}_' . $this->environment . '.yaml');
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/' . $this->environment . '/*.yaml');
        $routes->import($confDir . '/{routes}/*.yaml');
        if (is_file($confDir . '/routes.yaml')) {
            $routes->import($confDir . '/routes.yaml');
        }
    }
}
