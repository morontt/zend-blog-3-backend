<?php

namespace App\DependencyInjection\Security\Factory;

use App\Security\Authentication\Provider\WsseAuthenticationProvider;
use App\Security\Firewall\WsseAuthenticationListener;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WsseFactory implements SecurityFactoryInterface
{
    /**
     * @param mixed[] $config
     *
     * @return string[]
     */
    public function create(ContainerBuilder $container, string $id, array $config, string $userProvider, ?string $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.wsse.' . $id;
        $container
            ->setDefinition($providerId, new ChildDefinition(WsseAuthenticationProvider::class))
            ->setArgument(0, new Reference($userProvider))
            ->setArgument(1, $config['lifetime'])
        ;

        $listenerId = 'security.authentication.listener.wsse.' . $id;
        $container->setDefinition($listenerId, new ChildDefinition(WsseAuthenticationListener::class));

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function getPosition(): string
    {
        return 'pre_auth';
    }

    public function getKey(): string
    {
        return 'wsse';
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $builder
     */
    public function addConfiguration(NodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode('lifetime')->defaultValue(300)->end()
            ->end(); // @phpstan-ignore-line
    }
}
