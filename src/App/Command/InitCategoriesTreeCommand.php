<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCategoriesTreeCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:init-tree:category')
            ->setDescription('Init nested-set tree for category')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $this->handleCategory();

        $output->writeln('');
        $output->writeln('<info>Update category tree</info>');

        $endTime = microtime(true);

        $output->writeln('');
        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );

        return 0;
    }

    private function handleCategory(): void
    {
        $handled = [];

        /** @var \App\Repository\CategoryRepository $categoryRepo */
        $categoryRepo = $this->em->getRepository(Category::class);

        $qb = $categoryRepo->createQueryBuilder('c');
        $qb->update()
            ->set('c.nestedSet.leftKey', ':null')
            ->set('c.nestedSet.rightKey', ':null')
            ->set('c.nestedSet.depth', 1)
            ->setParameter('null', null)
            ->getQuery()
            ->execute()
        ;

        $qb = $categoryRepo->createQueryBuilder('c');
        $qb->orderBy('c.name');

        /** @var Category[] $categories */
        $categories = $qb->getQuery()->getResult();
        $idx = 0;
        foreach ($categories as $category) {
            if (!$category->getParent()) {
                $ns = $category->getNestedSet();
                $ns
                    ->setLeftKey(++$idx)
                    ->setRightKey(++$idx)
                ;

                $handled[] = $category->getId();
            }
        }

        $this->em->flush();

        do {
            $updateTree = false;
            foreach ($categories as $category) {
                if (!in_array($category->getId(), $handled)
                    && $parent = $category->getParent()
                ) {
                    if (in_array($parent->getId(), $handled)) {
                        $this->em->refresh($parent);
                        $nsParent = $parent->getNestedSet();

                        $categoryRepo->addToTree($category, $nsParent->getRightKey(), $nsParent->getDepth() + 1);

                        $handled[] = $category->getId();
                        $updateTree = true;

                        break;
                    }
                }
            }
        } while ($updateTree);
    }
}
