<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 17:28
 */

namespace App\Service;

use App\Entity\TrackingAgent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class Tracking
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $agentName
     *
     * @return TrackingAgent
     */
    public function getTrackingAgent(string $agentName): TrackingAgent
    {
        $hash = md5($agentName);
        $agent = $this->em->getRepository(TrackingAgent::class)->findOneByHash($hash);
        if (!$agent) {
            $agent = new TrackingAgent();
            $agent->setUserAgent($agentName);

            $this->em->persist($agent);
            $this->em->flush($agent);
        }

        return $agent;
    }
}
