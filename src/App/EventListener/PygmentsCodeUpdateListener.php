<?php

namespace App\EventListener;

use App\Event\PygmentCodeEvent;
use App\Repository\PostRepository;
use App\Service\TextProcessor;
use Doctrine\ORM\EntityManagerInterface;

class PygmentsCodeUpdateListener
{
    public function __construct(
        private TextProcessor $textProcessor,
        private PostRepository $repository,
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * @param PygmentCodeEvent $event
     */
    public function __invoke(PygmentCodeEvent $event): void
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
