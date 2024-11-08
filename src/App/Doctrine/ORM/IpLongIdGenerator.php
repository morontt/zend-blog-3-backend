<?php

namespace App\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use RuntimeException;

class IpLongIdGenerator extends AbstractIdGenerator
{
    /**
     * @param EntityManagerInterface $em
     * @param object|null $entity
     *
     * @return mixed
     */
    public function generateId(EntityManagerInterface $em, $entity)
    {
        if (!$entity || !method_exists($entity, 'getIpAddress')) {
            throw new RuntimeException('IpLongIdGenerator not supported for ' . $entity ? get_class($entity) : 'null');
        }

        return ip2long($entity->getIpAddress());
    }
}
