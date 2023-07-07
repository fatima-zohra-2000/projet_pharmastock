<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\TailleCommande;
use App\Entity\Produit;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $tailleCommande = new TailleCommande();
        $commande->addTailleCommande($tailleCommande);

        $lastCommande = $commandeRepository->findOneBy([], ['id' => 'desc']);
        $lastNumCommande = $lastCommande ? $lastCommande->getNumCommande() : 0;
        $commande->setNumCommande($lastNumCommande + 1);

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $somme = 0;
            foreach ($commande->getTailleCommandes() as $tc) {
                $tc->setPrix($tc->getProduit()->getPrix());
                $somme += $tc->getPrix() * $tc->getQuantite();
                $entityManager->persist($tc); // cette ligne pour persister chaque tailleCommande
            }

            $montantTva = $somme * $commande->getTVA() / 100;
            $total = $somme + $montantTva;

            $user = $this->getUser();

            $commande->setUser($user);
            $commande->setTotal($total);
            $commande->setMantantTVA($montantTva);

            //on controlle l'ajout du client : si l'utilisateur le choisi parmi la liste on manipule la variable client,
            // s'il choisi de le créer on utilise le formulaire newCliient
            if ($form->has('newClient')) {
                $newClient = $form->get('newClient')->getData();
                if ($newClient) {
                    $entityManager->persist($newClient);
                    $commande->setClient($newClient);
                } else {
                    $client = $form->get('client')->getData();
                    $commande->setClient($client);
                }
            }

            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            foreach ($commande->getTailleCommandes() as $tailleCommande) {
                $entityManager->remove($tailleCommande);
            }
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/rapport', name: 'app_commande_rapport')]
    public function rapportCommande(Commande $commande): Response
    {
        $html = $this->renderView('rapports/commande.html.twig', ['commande' => $commande]);

        return new Response($html);
    }

    #[Route('/{id}/rapport/download', name: 'app_commande_rapport_download')]
    public function rapportCommandeDownload(Commande $commande): Response
    {
        //la fonction convertit la page html en pdf et permet de la télécharger (c'est fonctionnel)

        $html = $this->renderView('rapports/commande.html.twig', ['commande' => $commande]);

        $filename = 'rapport_commande_' . $commande->getId() . '.pdf';

        // Conversion du contenu HTML en PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();

        // Téléchargement du rapport PDF
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        ));

        return $response;
    }

    // La fonction qui vient est à voir si la fonction précédente n'est pas fonctionnelle avec l'application de bureau

//    #[Route('/{id}/rapport', name: 'app_commande_rapport')]
//    public function rapportCommande(Commande $commande): Response
//    {
//        $html = $this->renderView('rapports/commande.html.twig', ['commande' => $commande]);
//
//        // Conversion du contenu HTML en PDF
//        $dompdf = new Dompdf();
//        $dompdf->loadHtml($html);
//        $dompdf->setPaper('A4', 'portrait');
//        $dompdf->render();
//        $pdfContent = $dompdf->output();
//
//        // Enregistrement du fichier PDF localement
//        $filename = 'rapport_commande_' . $commande->getId() . '.pdf';
//        $filePath = '/chemin/vers/dossier/rapports/' . $filename;
//        file_put_contents($filePath, $pdfContent);
//
//        // Redirection vers la page de visualisation du rapport
//        return $this->redirectToRoute('app_commande_index', ['filename' => $filename]);
//    }

}
