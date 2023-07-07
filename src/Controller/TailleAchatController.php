<?php

namespace App\Controller;

use App\Entity\TailleAchat;
use App\Form\TailleAchatType;
use App\Repository\TailleAchatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/taille/achat')]
class TailleAchatController extends AbstractController
{
    #[Route('/', name: 'app_taille_achat_index', methods: ['GET'])]
    public function index(TailleAchatRepository $tailleAchatRepository): Response
    {
        return $this->render('taille_achat/index.html.twig', [
            'taille_achats' => $tailleAchatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_taille_achat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TailleAchatRepository $tailleAchatRepository): Response
    {
        $tailleAchat = new TailleAchat();
        $form = $this->createForm(TailleAchatType::class, $tailleAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tailleAchatRepository->save($tailleAchat, true);

            return $this->redirectToRoute('app_taille_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taille_achat/new.html.twig', [
            'taille_achat' => $tailleAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taille_achat_show', methods: ['GET'])]
    public function show(TailleAchat $tailleAchat): Response
    {
        return $this->render('taille_achat/show.html.twig', [
            'taille_achat' => $tailleAchat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_taille_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TailleAchat $tailleAchat, TailleAchatRepository $tailleAchatRepository): Response
    {
        $form = $this->createForm(TailleAchatType::class, $tailleAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tailleAchatRepository->save($tailleAchat, true);

            return $this->redirectToRoute('app_taille_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taille_achat/edit.html.twig', [
            'taille_achat' => $tailleAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taille_achat_delete', methods: ['POST'])]
    public function delete(Request $request, TailleAchat $tailleAchat, TailleAchatRepository $tailleAchatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tailleAchat->getId(), $request->request->get('_token'))) {
            $tailleAchatRepository->remove($tailleAchat, true);
        }

        return $this->redirectToRoute('app_taille_achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
