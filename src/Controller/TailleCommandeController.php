<?php

namespace App\Controller;

use App\Entity\TailleCommande;
use App\Form\TailleCommandeType;
use App\Repository\TailleCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/taille/commande')]
class TailleCommandeController extends AbstractController
{
    #[Route('/', name: 'app_taille_commande_index', methods: ['GET'])]
    public function index(TailleCommandeRepository $tailleCommandeRepository): Response
    {
        return $this->render('taille_commande/index.html.twig', [
            'taille_commandes' => $tailleCommandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_taille_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TailleCommandeRepository $tailleCommandeRepository): Response
    {
        $tailleCommande = new TailleCommande();
        $form = $this->createForm(TailleCommandeType::class, $tailleCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tailleCommandeRepository->save($tailleCommande, true);

            return $this->redirectToRoute('app_taille_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taille_commande/new.html.twig', [
            'taille_commande' => $tailleCommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taille_commande_show', methods: ['GET'])]
    public function show(TailleCommande $tailleCommande): Response
    {
        return $this->render('taille_commande/show.html.twig', [
            'taille_commande' => $tailleCommande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_taille_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TailleCommande $tailleCommande, TailleCommandeRepository $tailleCommandeRepository): Response
    {
        $form = $this->createForm(TailleCommandeType::class, $tailleCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tailleCommandeRepository->save($tailleCommande, true);

            return $this->redirectToRoute('app_taille_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taille_commande/edit.html.twig', [
            'taille_commande' => $tailleCommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taille_commande_delete', methods: ['POST'])]
    public function delete(Request $request, TailleCommande $tailleCommande, TailleCommandeRepository $tailleCommandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tailleCommande->getId(), $request->request->get('_token'))) {
            $tailleCommandeRepository->remove($tailleCommande, true);
        }

        return $this->redirectToRoute('app_taille_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
