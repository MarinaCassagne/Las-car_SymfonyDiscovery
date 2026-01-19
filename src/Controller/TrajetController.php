<?php

namespace App\Controller;

use App\Entity\Trajet; // Entité Trajet pour pouvoir instancier un trajet
use App\Entity\User; // Entité User pour pouvoir instancier récupérer IdUser
use App\Entity\Moderation; // Entité Moderation pour pouvoir instancier récupérer IdModeration
use App\Form\TrajetType;
use App\Repository\TrajetRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface; // pour sauvergarder ou supprimer dans la BDD
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // classe parente pour utiliser des méthodes spécifique comme render() pour renvoyer une vue ou json() pour renvoyer un tableau en JSON
use Symfony\Component\HttpFoundation\JsonResponse; // pour renvoyer une réponse au format JSON
use Symfony\Component\HttpFoundation\Request; // pour utiliser les methodes HTTP: GET, POST, DELETE, UPDATE... 
use Symfony\Component\HttpFoundation\Response; // pour renvoyer les codes HTTP (202, 404, ...)
use Symfony\Component\Routing\Attribute\Route; // pour définir des URLs
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

#[Route('/trajet')]
final class TrajetController extends AbstractController
{   
    // URL pour avoir la liste des trajets
    #[Route('/api/trajets',name: 'app_trajets_list', methods: ['GET'])]
    
    // ========================================================================
    //                        LISTER TOUS LES TRAJETS
    // ========================================================================
    
    // Lister les trajets sous format JSON
    public function list(TrajetRepository $trajetRepository): JsonResponse
    {
        // Permet de mettre dans un tableau le résulat de la fonction 
        $trajets = array_map(
            
            fn(Trajet $trajet)=> $this->serializeTrajet($trajet),

            // Récupère tous les trajets de la BDD
            $trajetRepository->findAll()
        );

        // Renvoie un tableau des trajets en format JSON avec le code HTTP 200, si réponse serveur (OK)
        return $this->json($trajets);
    }

    // ========================================================================
    //                        CRÉER UN TRAJET
    // ========================================================================

    #[Route('/api/trajets', name: 'app_trajet_create', methods: ['POST'])]

    // Crée un nouveau trajet et le sauvegarder en base de données
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Décode le JSON envoyé dans le corps de la requête
        // Transforme {"date_de_depart":2026-09-23 08:25:34,"prix":3,40} en tableau associatif PHP ['date_de_depart'=>'2026-09-23 08:25:34','prix'=>3,40]
        $data = $this->decodeJson($request);
        
        if ($data === null)
        {
           // Si le JSON est invalide, retourne une erreur 400 (Bad Request)
           return $this->errorResponse('Invalid JSON body.', Response::HTTP_BAD_REQUEST);
        }
              
        // ==============================================
        //         VÉRIFICATION / VALIDATION 
        // ==============================================

            //============= DATE DE DÉPART ==================
            // Récupérer la date de départ dans les données
            $date_de_depart = $data['date_de_depart']?? '';

            // Nettoyer et valider la data $date_de_depart
            // TODO AJOUTER LA VÉRIFICATION DU FORMAT DE LA DATE : Utiliser DateTimeValidator ?
            // Si la date de départ n'est pas au bon format, 
            // if ()
            // {
            //     Alors retourne une erreur
            //     return $this->errorResponse('Departure date is not format Y-m-d H:i:s.', Response::HTTP_BAD_REQUEST);
            // }

            // Si la date de départ n'est pas renseignée, 
            if ($date_de_depart === '') {
                // alors retourne une erreur
                return $this->errorResponse('Departure date is required.', Response::HTTP_BAD_REQUEST);
            }
            
            //============= "ADRESSES" ==================

            // TODO ⚠️ AJOUTER LA VÉRIFICATION : SI LES ADRESSES EXISTENT
            // TODO AJOUTER  les attributs $adresse_lieu_depart_conducteur et $adresse_lieu_arrive_conducteur
            
                //=== ADRESSE LIEU DE DÉPART==========

                $longitude_lieu_depart_conducteur = $data['longitude_lieu_depart_conducteur']?? '';
                // Nettoyer et valider la data $longitude_lieu_depart_conducteur
                if ($longitude_lieu_depart_conducteur === '') {
                    // Si la longitude du lieu de depart du conducteur est vide, retourne une erreur
                    return $this->errorResponse('Longitude departure location driver is required.', Response::HTTP_BAD_REQUEST);
                }

                $latitude_lieu_depart_conducteur = $data['latitude_lieu_depart_conducteur']?? '';
                // Nettoyer et valider la data $latitude_lieu_depart_conducteur
                if ($latitude_lieu_depart_conducteur === '') {
                    // Si la latitude du lieu de depart du conducteur est vide, retourne une erreur
                    return $this->errorResponse('Latitude location departure driver is required.', Response::HTTP_BAD_REQUEST);
                }

                //=== ADRESSE LIEU DE D'ARRIVÉ ==========
                
                $longitude_lieu_arrive_conducteur = $data['longitude_lieu_arrive_conducteur']?? '';
                // Nettoyer et valider la data $longitude_lieu_arrive_conducteur
                if ($longitude_lieu_arrive_conducteur === '') {
                    // Si la la longitude du lieu d'arrivé du conducteur est vide, retourne une erreur
                    return $this->errorResponse('Longitude location arrival driver is required.', Response::HTTP_BAD_REQUEST);
                }

                $latitude_lieu_arrive_conducteur = $data['latitude_lieu_arrive_conducteur']?? '';
                // Nettoyer et valider la latitude du lieu de d'arrivée conducteur
                if ($latitude_lieu_arrive_conducteur === '') {
                    // Si la latitude du lieu d'arrivé du conducteur est vide, retourne une erreur
                    return $this->errorResponse('Latitude location arrival driver is required.', Response::HTTP_BAD_REQUEST);
                }

            

            //============= DURÉE ==================
            // TODO UTILISER API POUR CALCULER LA DURÉE EN UTILISANT LES COORDONNÉES GPS DU LIEU DE DÉPART ET D'ARRIVÉE
            //  Ressource :https://distancematrix.ai/fr/guides/easy-migrate
                $duree = 10;

            // Nettoyer et valider la duree
            if ($duree === '') {
                // Si la duree est vide, retourne une erreur
                return $this->errorResponse('Number of places is required.', Response::HTTP_BAD_REQUEST);
            }

            //============= NOMBRE DE KM ==================
            // TODO UTILISER API POUR CALCULER LE NOMBRE DE KM EN UTILISANT LES COORDONNÉES GPS DU LIEU DE DÉPART ET D'ARRIVÉE
                $nombre_de_km = 40;
             if ($nombre_de_km === '') {
                // Si le nombre de km est vide, retourne une erreur
                return $this->errorResponse('Number of kilometers is required.', Response::HTTP_BAD_REQUEST);
            }

            //============= NOMBRE DE PLACES ==================
            $nombre_de_place = $data['nombre_de_place']?? '';
            if ($nombre_de_place === '') {
                // Si le nombre de place est vide, retourne une erreur
                return $this->errorResponse('Number of places is required.', Response::HTTP_BAD_REQUEST);
            }

            //============= PRIX ==================
            $priceError = null;
            $prix = $this->parsePrice($data['prix'] ?? null, true, $priceError);
            
            if ($prix === null) {
                // Si le prix est vide, retourne une erreur
                return $this->errorResponse($priceError ?? 'Invalid price.', Response::HTTP_BAD_REQUEST);
            }

            //======== DATE DE PUBLICATION ========
            // Récupérer la date de publication dans les données
            $date_de_publication = $data['date_de_publication']?? '';

            // Nettoyer et valider la data $date_de_publication
            // TODO AJOUTER LA VÉRIFICATION DU FORMAT DE LA DATE : Utiliser DateTimeValidator ?
            // Si la date de publication n'est pas au bon format, 
            // if ()
            // {
            //     Alors retourne une erreur
            //     return $this->errorResponse('Publication date is not format Y-m-d H:i:s.', Response::HTTP_BAD_REQUEST);
            // }

            // Si la date de publication n'est pas renseignée, 
            if ($date_de_publication === '') {
                // alors retourne une erreur
                return $this->errorResponse('Publication date is required.', Response::HTTP_BAD_REQUEST);
            }

            //======== NATURE DU TRAJET ========
            $nature_trajet = $data['nature_trajet']?? '';
            // Si la nature du trajet n'est pas égale à la liste des enum, 
            // TODO https://symfony.com/doc/current/ai/components/agent.html#automatic-enum-validation
            
            // Si la nature du trajet n'est pas renseigné
            if ($nature_trajet === '') {
                // alors retourne une erreur
                return $this->errorResponse('Nature of the journey is required.', Response::HTTP_BAD_REQUEST);
            }

            //======== TYPE DE TRAJET ========
            $type_trajet = $data['type_trajet']?? '';

            // Si le type du trajet n'est pas égale à la liste des enum, 
            // TODO https://symfony.com/doc/current/ai/components/agent.html#automatic-enum-validation
            
            // Si le type du trajet n'est pas renseigné,
            if ($type_trajet === '') {
                // alors retourne une erreur
                return $this->errorResponse('Type of journey is required.', Response::HTTP_BAD_REQUEST);
            }

            //======== STATUT VALIDE TRAJET ========
            $statut_valide = $data['$statut_valide']?? '';
            
            // Si le type du trajet n'est pas égale à la liste des enum, 
            // TODO https://symfony.com/doc/current/ai/components/agent.html#automatic-enum-validation

            // Si le statut du trajet n'est pas renseigné,
            if ($type_trajet === '') {
                // alors retourne une erreur
                return $this->errorResponse('Type of journey is required.', Response::HTTP_BAD_REQUEST);
            }




        // ==============================================
        //         CRÉATION D'UN TRAJET 
        // ==============================================    

        $trajet = (new Trajet())
            ->setDateDeDepart($date_de_depart)
            ->setLongitudeLieuDepartConducteur($longitude_lieu_depart_conducteur)
            ->setLatitudeLieuDepartConducteur($latitude_lieu_depart_conducteur)
            ->setLongitudeLieuArriveConducteur($longitude_lieu_arrive_conducteur)
            ->setLatitudeLieuArriveConducteur($latitude_lieu_arrive_conducteur)
            ->setDuree($duree)
            ->setNombreDeKm($nombre_de_km)
            ->setNombreDePlace($nombre_de_place)
            ->setPrix($prix)
            ->setNatureTrajet($nature_trajet)
            ->setTypeTrajet($type_trajet)
            ->setStatutValide($statut_valide)
            ->setIdModeration($idModeration)
            ->setUser($User)
            ->setIdEtapeTrajet($idEtapeTrajet)
            ->setIdReservation($idReservation)
            ->setDateDePublication($date_de_publication);

    
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        // Sauvegarder le trajet dans la BDD
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
    // ========================================================================
    //                        MÉTHODES PRIVÉES
    // ========================================================================

    /**
     * json_decode() transforme une chaîne JSON en tableau PHP
     * 
     */ 
    private function decodeJson(Request $request): ?array
    {
        // Le paramètre true force la conversion en tableau (sinon ce serait un objet)
        $payload = json_decode($request->getContent(), true);

        // Vérifie que c'est bien un tableau ET qu'il n'y a pas d'erreur JSON
        if (!is_array($payload) || json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $payload;
    }

    private function errorResponse(string $message, int $status): JsonResponse
    {
        return $this->json(['error' => $message], $status);
    }

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
                'date_de_publication' => $trajet->getDateDePublication()->format('Y-m-d H:i:s'),
                'nature_trajet' => $trajet->getNatureTrajet(),
                'type_trajet' => $trajet->getTypeTrajet(),
                'statut_valide' => $trajet->getStatutValide(),
                'idModeration' => $trajet->getIdModeration(),
                'User' => $trajet->getUser(),
                'idEtapeTrajet' => $trajet->getIdEtapeTrajet(),
                'idReservation' => $trajet->getIdReservation()        
            ];
        }

    /**
     * Valide et vérifie la data : prix
     * @param mixed $value
     * @param bool $required
     * @param mixed $error
     * @return string|null
     */
    private function parsePrice(mixed $value, bool $required, ?string &$error): ?string
    {
        // Vérifie si la valeur est vide
        if ($value === null || $value === '') {
            if ($required) {
                $error = 'Price is required.';
            }
            return null;
        }

        // Vérifie si c'est un nombre
        if (!is_numeric($value)) {
            $error = 'Price must be numeric.';
            return null;
        }

        // Convertit en float et vérifie que c'est positif
        $price = (float) $value;
        if ($price < 0) {
            $error = 'Price must be positive.';
            return null;
        }

        // Formate le prix avec 2 décimales
        // number_format(999.5, 2, '.', '') → "999.50"
        return number_format($price, 2, '.', '');
    }
     
    /**
     * Valide et vérifie la data de l'enum
     * @return void
     * https://symfony.com/doc/current/ai/components/agent.html#automatic-enum-validation
     */
    private function parseEnum()
    {

    }

    private function parseDate()
    {

    }


}





