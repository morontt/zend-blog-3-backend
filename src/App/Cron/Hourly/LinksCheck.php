<?php

namespace App\Cron\Hourly;

use Doctrine\ORM\EntityManagerInterface;
use App\Cron\HourlyCronServiceInterface;
use App\Entity\Commentator;
use App\Utils\RottenLink;

class LinksCheck implements HourlyCronServiceInterface
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function run()
    {
        $repository = $this->em->getRepository(Commentator::class);
        foreach ($repository->getWithUncheckedLinks() as $commentator) {
            $commentator
                ->setRottenCheck(new \DateTime())
                ->setRottenLink(!RottenLink::doesWork($commentator->getWebsite()))
            ;
        }

        $this->em->flush();
    }

    public function getMessage(): ?string
    {
        return null;
    }
}
