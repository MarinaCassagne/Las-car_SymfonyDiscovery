<?php

namespace App\Controller;

use App\Entity\Trajet; // Entité Trajet pour pouvoir instancier un trajet
use App\Form\TrajetType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface; // pour sauvergarder ou supprimer dans la BDD
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // classe parente pour utiliser des méthodes spécifique comme render() pour renvoyer une vue ou json() pour renvoyer un tableau en JSON
use Symfony\Component\HttpFoundation\JsonResponse; // pour renvoyer une réponse au format JSON
use Symfony\Component\HttpFoundation\Request; // pour utiliser les methodes HTTP: GET, POST, DELETE, UPDATE... 
use Symfony\Component\HttpFoundation\Response; // pour renvoyer les codes HTTP (202, 404, ...)
use Symfony\Component\Routing\Attribute\Route; // pour définir des URLs

#[Route('/trajet')]
final class TrajetController extends AbstractController
{   
    // URL pour avoir la liste des trajets
    #[Route('/api/trajets',name: 'app_trajets_list', methods: ['GET'])]
    
    // ========================================================================
    //                        LISTER TOUS LES TRAJETS
    // ========================================================================
    
    // Liste les trajets sous format JSON
    public function list(TrajetRepository $trajetRepository): JsonResponse
    {
        // Permet de mettre dans un tableau le résulat de la fonction 
        $trajets = array_map(
            
            fn(Trajet $trajet)=> $this->serializeTrajet($trajet),

            // Récupère tous les trajets de la BDD
            $trajetRepository->findAll()
        );

        // Renvoie un tableau des trajets en format JSON avec le code HTTP 200 si réponse serveur OK
        return $this->json($trajets);
    }

    // ========================================================================
    //                        CRÉER UN TRAJET
    // ========================================================================

    #[Route('/api/create', name: 'app_trajet_create', methods: ['GET', 'POST'])]

    // Crée un nouveau trajet et le sauvegarde en base de données
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trajet = new Trajet();
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trajet);
            $entityManager->flush();

            return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trajet/new.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trajet_show', methods: ['GET'])]
    public function show(Trajet $trajet): Response
    {
        return $this->render('trajet/show.html.twig', [
            'trajet' => $trajet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trajet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trajet $trajet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trajet/edit.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trajet_delete', methods: ['POST'])]
    public function delete(Request $request, Trajet $trajet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trajet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trajet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
    }

    //================ MÉTHODES SPÉCIFIQUES ===============

/**
 * Transforme un objet Trajet en tableau associatif
 * @param Trajet $trajet: L'objet Trajet à convertir
 */
private function serializeTrajet(Trajet $trajet): array
    {
        return [
            'id' => $trajet->getId(),
            'date_de_depart' => $trajet->getDateDeDepart(),
            'longitude_lieu_depart_conducteur' => $trajet->getLongitudeLieuDepartConducteur(),
            'latitude_lieu_depart_conducteur' => $trajet->getLatitudeLieuDepartConducteur(),
            'longitude_lieu_arrive_conducteur' => $trajet->getLongitudeLieuArriveConducteur(),
            'latitude_lieu_arrive_conducteur' => $trajet->getLatitudeLieuArriveConducteur(),
            'duree' => $trajet->getDuree(),
            'nombre_de_km' => $trajet->getNombreDeKm(),
            'nombre_de_place' => $trajet->getNombreDePlace(),
            'prix' => $trajet->getPrix(),
            'date_de_publication' => $trajet->getDateDePublication()->format(\DateTimeInterface::ATOM), // Format ATOM = format ISO 8601 : "2026-01-13T10:30:00+00:00"
            'nature_trajet' => $trajet->getNatureTrajet(),
            'type_trajet' => $trajet->getTypeTrajet(),
            'statut_valide' => $trajet->getStatutValide(),
            'idModeration' => $trajet->getIdModeration(),
            'User' => $trajet->getUser(),
            'idEtapeTrajet' => $trajet->getIdEtapeTrajet(),
            'idReservation' => $trajet->getIdReservation()        
        ];
    }
}


