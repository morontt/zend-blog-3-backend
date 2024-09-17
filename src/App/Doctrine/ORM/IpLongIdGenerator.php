<?php

namespace App\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class IpLongIdGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        if (!$entity || !method_exists($entity, 'getIpAddress')) {
            throw new \RuntimeException('IpLongIdGenerator not supported for ' . $entity ? get_class($entity) : 'null');
        }

        return ip2long($entity->getIpAddress());
    }
}
