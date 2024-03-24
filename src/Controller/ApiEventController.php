<?php

namespace App\Controller;

use App\Entity\Calendrier;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiEventController extends AbstractController
{
    #[Route('/api/event', name: 'app_api_event')]
    public function index(): Response
    {
        return $this->render('api_event/index.html.twig', [
            'controller_name' => 'ApiEventController',
        ]);
    }
    #[Route('/api/{id}/edit', name: 'api_event_edit', methods:"PUT")]
    public function majEvent(?Calendrier $calendrier, Request $request, EntityManagerInterface $em)
    {
        // on récupère les données
        $donnees = json_decode($request->getContent());
        if (
            isset($donnees->title) && !empty($donnees->title ) &&
            isset($donnees->start) && !empty($donnees->start ) &&
            isset($donnees->description) && !empty($donnees->description ) &&
            isset($donnees->color) && !empty($donnees->color )
        ) {
            // les données sont complètes
            $code = 200;
            // on verifie si l'id existe
            if(!$calendrier){
                // on instancie un evenement
                $calendrier = new Calendrier();
                //on change le code
                $code = 201;
            }

            // on hydrate l'objet avec les données
            $calendrier->setTitre($donnees->title);
            $calendrier->setDescription($donnees->description);
            $calendrier->setDebut(new DateTime($donnees->start));
            $calendrier->setTitre($donnees->title);


            if($donnees->allDay){
                $calendrier->setFin(new DateTime($donnees->start));
            }else{
                $calendrier->setFin(new DateTime($donnees->end));
            }
            $calendrier->setAllDay($donnees->allDay);
            $calendrier->setCouleur($donnees->color);

            $em->persist($calendrier);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }

        //return $this->render('api_event/index.html.twig', [
        //    'controller_name' => 'ApiEventController',
        //]);
    }
}
