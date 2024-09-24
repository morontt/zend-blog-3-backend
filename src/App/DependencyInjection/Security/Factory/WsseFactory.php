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
     * @param ContainerBuilder $container
     * @param $id
     * @param $config
     * @param $userProvider
     * @param $defaultEntryPoint
     *
     * @return array
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
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

    /**
     * @return string
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'wsse';
    }

    /**
     * @param NodeDefinition $builder
     */
    public function addConfiguration(NodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('lifetime')->defaultValue(300)->end()
            ->end();
    }
}
