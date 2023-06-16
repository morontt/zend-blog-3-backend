<?php

namespace Mtt\BlogBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class IpLongIdGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        if (method_exists($entity, 'getIpAddress')) {
            throw new \RuntimeException('IpLongIdGenerator not supported for ' . get_class($entity));
        }

        return ip2long($entity->getIpAddress());
    }
}
