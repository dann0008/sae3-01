<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Entity\Classe;
use App\Form\CalendrierType;
use App\Repository\CalendrierRepository;
use App\Repository\ClasseRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendrier')]
class CalendrierController extends AbstractController
{
    #[Route('/', name: 'app_calendrier_index', methods: ['GET'])]
    public function index(CalendrierRepository $calendrierRepository): Response
    {
        if ($this->isGranted('ROLE_PROFESSEUR' )){
            $events = $this->getUser()->getCours();
        }elseif($this->isGranted('ROLE_ETUDIANT' )){
            $events=[];
            $lstclasse = $this->getUser()->getClasses();
            foreach($lstclasse as $classe){
                $eventsClass= $classe->getCours();
                foreach ($eventsClass as $event){
                    $events[]= $event;
                }
            }
            $prvEvents = $this->getUser()->getCours();
            foreach ($prvEvents as $event){
                $events[]= $event;
            }

        } else{
            $events=[];
        }
        $rdvs = [];

        foreach($events as $event){
            $rdvs[]= [
                'id'=> $event->getId(),
                'start'=> $event->getDebut()->format('Y-m-d H:i:s'),
                'end'=> $event->getFin()->format('Y-m-d H:i:s'),
                'title'=> $event->getTitre(),
                'description'=> $event->getDescription(),
                'backgroundColor'=> $event->getCouleur(),
                'allDay'=> $event->isAllDay(),
            ];
        }

        $data = json_encode($rdvs);

        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isGranted('ROLE_PROFESSEUR' ) or $this->isGranted('ROLE_ADMIN' )){
            return $this->render('calendrier/index.html.twig', [
                'calendriers' => $calendrierRepository->findAll(), 'data'=> $data
            ]);
        }

        elseif($this->isGranted('ROLE_ETUDIANT' )){
            return $this->render('calendrier/indexEtud.html.twig', [
                'calendriers' => $calendrierRepository->findAll(), 'data'=> $data
            ]);
        } else{
            return $this->render('home/index.html.twig');
        }
    }

    #[Route('/{id}', name: 'app_calendrier_show', methods: ['GET'])]
    public function show(Calendrier $calendrier): Response
    {
        return $this->render('calendrier/show.html.twig', [
            'calendrier' => $calendrier,
        ]);
    }




    #[Route('/new', name: 'app_calendrier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CalendrierRepository $calendrierRepository, UtilisateurRepository $utilisateurRepository, ClasseRepository $classeRepository): Response
    {
        if ($this->isGranted('ROLE_PROFESSEUR' ) or $this->isGranted('ROLE_ADMIN' )) {

            $calendrier = new Calendrier();

            $form = $this->createForm(CalendrierType::class, $calendrier, [
                'action' => $this->generateUrl('app_calendrier_new'),
                'method' => 'POST'
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getUser()->addCour($calendrier);
                $utilisateurRepository->save($this->getUser());
                $calendrierRepository->save($calendrier, true);
                foreach ($calendrier->getClasses() as $class) {
                    $class->addCour($calendrier);
                    $classeRepository->save($class, true);
                }

                return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('calendrier/new.html.twig', [
                'calendrier' => $calendrier,
                'form' => $form,
            ]);
        }else{
            return $this->render('home/index.html.twig');

        }
    }

    #[Route('/{id}/edit', name: 'app_calendrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendrier $calendrier, CalendrierRepository $calendrierRepository, UtilisateurRepository $utilisateurRepository, ClasseRepository $classeRepository): Response
    {
        if ($this->isGranted('ROLE_PROFESSEUR' ) or $this->isGranted('ROLE_ADMIN' )) {

            $form = $this->createForm(CalendrierType::class, $calendrier, [
            'action' => $this->generateUrl('app_calendrier_edit', ['id' => $calendrier->getId()]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->addCour($calendrier);
            $classes = $calendrier->getClasses();
            $allClasses = $classeRepository->findAll();
            $calendrier->removeAllClasses();
            $utilisateurRepository->save($this->getUser());
            $calendrierRepository->save($calendrier, true);
            foreach ($allClasses as $class) {
                if (in_array($class, $classes->toArray())) {
                    $class->addCour($calendrier);
                } else {
                    $class->removeCour($calendrier);
                }
                $classeRepository->save($class, true);
            }

            return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendrier/edit.html.twig', [
            'calendrier' => $calendrier,
            'form' => $form,
        ]);
        }else{
            return $this->render('home/index.html.twig');

            }
    }

    #[Route('/{id}', name: 'app_calendrier_delete', methods: ['POST'])]
    public function delete(Request $request, Calendrier $calendrier, CalendrierRepository $calendrierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendrier->getId(), $request->request->get('_token'))) {
            $calendrierRepository->remove($calendrier, true);
        }

        return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
    }
}
