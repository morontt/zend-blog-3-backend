<?php

namespace App\EventListener;

use App\Event\PygmentCodeEvent;
use App\Repository\PostRepository;
use App\Service\TextProcessor;
use Doctrine\ORM\EntityManagerInterface;

class PygmentsCodeUpdateListener
{
    private TextProcessor $textProcessor;

    private PostRepository $repository;

    private EntityManagerInterface $em;

    /**
     * @param TextProcessor $textProcessor
     * @param PostRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TextProcessor $textProcessor,
        PostRepository $repository,
        EntityManagerInterface $em,
    ) {
        $this->textProcessor = $textProcessor;
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @param PygmentCodeEvent $event
     */
    public function __invoke(PygmentCodeEvent $event)
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
