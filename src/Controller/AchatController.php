<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\TailleAchat;
use App\Form\AchatType;
use App\Repository\AchatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

//#[IsGranted('ROLE_ADMIN')]
#[Route('/achat')]
class AchatController extends AbstractController
{
    #[Route('/', name: 'app_achat_index', methods: ['GET'])]
    public function index(AchatRepository $achatRepository): Response
    {
        return $this->render('achat/index.html.twig', [
            'achats' => $achatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_achat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AchatRepository $achatRepository, EntityManagerInterface $entityManager): Response
    {
            $achat = new Achat();
            $tailleAchat = new TailleAchat();
            $achat->addTailleAchat($tailleAchat);

            $lastAchat = $achatRepository->findOneBy([], ['id' => 'desc']);
            $lastNumAchat = $lastAchat ? $lastAchat->getNumAchat() : 0;
            $achat->setNumAchat($lastNumAchat + 1);

            $form = $this->createForm(AchatType::class, $achat);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $total = 0;
                foreach ($achat->getTailleAchats() as $tailleAchat) {
                    $tailleAchat->setPrix($tailleAchat->getProduit()->getPrix());
                    $total += $tailleAchat->getPrix() * $tailleAchat->getQuantite();
                    $entityManager->persist($tailleAchat);
                }

                $montantTva = $total * $achat->getTVA() / 100;
                $totalTTC = $total + $montantTva;

                $user = $this->getUser();

                $achat->setUser($user);
                $achat->setMantantTVA($montantTva);
                $achat->setTotal($totalTTC);

                $entityManager->persist($achat);
                $entityManager->flush();

                return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
            }

        return $this->renderForm('achat/new.html.twig', [
            'achat' => $achat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_achat_show', methods: ['GET'])]
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Achat $achat, AchatRepository $achatRepository): Response
    {
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatRepository->save($achat, true);

            return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat/edit.html.twig', [
            'achat' => $achat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_achat_delete', methods: ['POST'])]
    public function delete(Request $request, Achat $achat, AchatRepository $achatRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achat->getId(), $request->request->get('_token'))) {
            foreach ($achat->getTailleAchats() as $tailleAchat) {
                $entityManager->remove($tailleAchat);
            }
            $entityManager->remove($achat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/rapport', name: 'app_achat_rapport')]
    public function rapportAchat(Achat $achat): Response
    {
        $html = $this->renderView('rapports/achat.html.twig', ['achat' => $achat]);

        return new Response($html);
    }

    #[Route('/{id}/rapport/download', name: 'app_achat_rapport_download')]
    public function rapportAchatDownload(Achat $achat): Response
    {
        $html = $this->renderView('rapports/achat.html.twig', ['achat' => $achat]);

        $filename = 'rapport_achat_' . $achat->getId() . '.pdf';

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
}
