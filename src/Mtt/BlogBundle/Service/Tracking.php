<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 17:28
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\TrackingAgent;

class Tracking
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
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
        $agent = $this->em->getRepository('MttBlogBundle:TrackingAgent')->findOneByHash($hash);
        if (!$agent) {
            $agent = new TrackingAgent();
            $agent->setUserAgent($agentName);

            $this->em->persist($agent);
            $this->em->flush($agent);
        }

        return $agent;
    }
}
