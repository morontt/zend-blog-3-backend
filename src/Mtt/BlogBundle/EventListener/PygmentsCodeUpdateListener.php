<?php

namespace Mtt\BlogBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Entity\Repository\PostRepository;
use Mtt\BlogBundle\Event\PygmentCodeEvent;
use Mtt\BlogBundle\Service\TextProcessor;

class PygmentsCodeUpdateListener
{
    /**
     * @var TextProcessor
     */
    private $textProcessor;

    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param TextProcessor $textProcessor
     * @param PostRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TextProcessor $textProcessor,
        PostRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->textProcessor = $textProcessor;
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @param PygmentCodeEvent $event
     */
    public function onUpdate(PygmentCodeEvent $event)
    {
        $posts = $this->repository->getPostsByCodeSnippet($event->getPygmentsCode()->getId());
        if (count($posts)) {
            foreach ($posts as $post) {
                $this->textProcessor->processing($post);
            }
            $this->em->flush();
        }
    }
}
