<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\StockType;
use App\Repository\ProduitRepository;
use App\Entity\Stock;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, ProduitRepository $produitRepository): Response
    {
        // Récupérez la liste complète des produits
        $produits = $produitRepository->findAll();

        // Paginez les produits
        $pagination = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1), // Récupérez le numéro de page à partir de la requête, par défaut 1
            7 // Nombre de produits par page
        );

        return $this->render('produit/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, StockRepository $stockRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            dump($form->getErrors(true, false));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            $stock = new Stock();
            $stock->setQuantite($form->get('Stock')->get('quantite')->getData()); //Avant c'était' : $form->get('quantite')->getData() c'était ça quia causé l'erreur Child "quantite" does not exist.
            $stock->setProduitId($produit);
            $stock->setFournisseurId($form->get('Stock')->get('fournisseur_id')->getData()); //même modification est faite ici

            $stockRepository->save($stock, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        $stock = $produit->getStock();
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'stock' => $stock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository, StockRepository $stockRepository): Response
    {

        // Récupération de l'objet Stock associé au produit
        $stock = $stockRepository->findOneBy(['produit_id' => $produit->getId()]);

        // Vérification que l'objet Stock existe bien avant de l'ajouter au formulaire
        if (!$stock) {
            throw $this->createNotFoundException('Stock not found for product '.$produit->getId());
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->get('Stock')->setData($stock); // Pré-remplir les données de Stock dans le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            $stock->setQuantite($form->get('Stock')->get('quantite')->getData());
            $stock->setFournisseurId($form->get('Stock')->get('fournisseur_id')->getData());
            $stockRepository->save($stock, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'stock' => $stock, // On passe l'objet Stock à la vue
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editstock', name: 'app_stock_edit', methods: ['GET', 'POST'])]
    public function editStock(Request $request, Produit $produit, StockRepository $stockRepository): Response
    {
        // Récupération de l'objet Stock associé au produit
        $stock = $stockRepository->findOneBy(['produit_id' => $produit->getId()]);

        // Vérification que l'objet Stock existe bien avant d'afficher le formulaire
        if (!$stock) {
            throw $this->createNotFoundException('Stock not found for product '.$produit->getId());
        }

        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockRepository->save($stock, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/edit.html.twig', [
            'produit' => $produit,
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
