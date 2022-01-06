<?php

namespace Mtt\BlogBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Entity\Category;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitNestedSetTreeCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->em->getConfiguration()->setSQLLogger(null);
    }

    protected function configure()
    {
        $this
            ->setName('mtt:tree:init')
            ->setDescription('Init nested-set tree for category and comments')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $this->handleCategory();

        $output->writeln('');
        $output->writeln('<info>Update category tree</info>');

        $endTime = microtime(true);

        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );
    }

    private function handleCategory()
    {
        $handled = [];

        $categoryRepo = $this->em->getRepository('MttBlogBundle:Category');
        $qb = $categoryRepo->createQueryBuilder('c');
        $qb->orderBy('c.name');

        /* @var Category[] $categories */
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

                        $categoryRepo->addToTree($nsParent->getRightKey());

                        $ns = $category->getNestedSet();
                        $ns
                            ->setLeftKey($nsParent->getRightKey())
                            ->setRightKey($nsParent->getRightKey() + 1)
                            ->setDepth($nsParent->getDepth() + 1)
                        ;

                        $this->em->flush($category);

                        $handled[] = $category->getId();
                        $updateTree = true;

                        break;
                    }
                }
            }
        } while ($updateTree);
    }
}
