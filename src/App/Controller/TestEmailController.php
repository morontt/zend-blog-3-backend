<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestEmailController extends AbstractController
{
    /**
     * @Route("/test-emails", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function indexAction(): array
    {
        return [];
    }

    /**
     * @Route("/test-emails", methods={"POST"})
     *
     * @param Mailer $mailer
     * @param CommentRepository $repository
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function sendAction(Mailer $mailer, CommentRepository $repository, Request $request): RedirectResponse
    {
        $mailTo = $request->request->get('_receiver');
        if (!empty($mailTo)) {
            $qb = $repository->createQueryBuilder('c');
            $qb
                ->select()
                ->orderBy('c.id', 'DESC')
                ->setMaxResults(1)
                ->setFirstResult(mt_rand(0, 500))
            ;

            $comment = $qb->getQuery()->getOneOrNullResult();

            $mailer->newComment($comment, $request->request->get('_receiver'), false);
        }

        return new RedirectResponse('/test-emails');
    }
}
