<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestEmailController extends AbstractController
{
    #[Route(path: '/admin/test-emails', methods: ['GET'], name: 'test_emails')]
    public function indexAction(): Response
    {
        return $this->render('test_email/index.html.twig');
    }

    /**
     * @param Mailer $mailer
     * @param CommentRepository $repository
     * @param Request $request
     *
     * @return RedirectResponse
     */
    #[Route(path: '/admin/test-emails', methods: ['POST'])]
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

        return $this->redirectToRoute('test_emails');
    }
}
