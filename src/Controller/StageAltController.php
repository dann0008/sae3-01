<?php

namespace App\Controller;

use App\Entity\StageAlt;
use App\Entity\Utilisateur;
use App\Form\StageAltType;
use App\Repository\StageAltRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StageAltController extends AbstractController
{
    #[Route('/stagealt', name: 'app_stage_alt')]
    public function index(StageAltRepository $stageAltRepositoryRepository, Request $request): Response
    {
        $researchText = $request->query->get('search', '');

        $stageAlt = $stageAltRepositoryRepository->search($researchText ?? '');

        return $this->render('stage_alt/index.html.twig', ['stageAlt' => $stageAlt, 'search' => $researchText]);
    }

    #[Route('stagealts/{id}', name: 'app_stage_alt_show')]
    public function show(StageAlt $stageAlt,Request $request): Response
    { // recup l'user
        $user = $this->getUser();
        $prop = False;
        // verification du propriétaire
        if ($stageAlt->getEntreprise() === $user) {
            $prop = True;
        }
        if($request->query->get('no_include') == 'true') {
            if ($prop) { // eviter les injections
                return $this->render('stage_alt/showEntrInterface.html.twig', ['stageAlt' => $stageAlt]);
            }
        }

        if (!$prop) {
            return $this->render('stage_alt/show.html.twig', ['stageAlt' => $stageAlt]);
        } else {
            return $this->render('stage_alt/show.html.twig', ['stageAlt' => $stageAlt]);
        }
    }


    #[Route('/stageAlt_create', name: 'app_stage_alt_create')]
    public function create(Request $request , ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');
        $manager = $doctrine->getManager();
        $stageAlt = new StageAlt();

        $stageAlt->setEntreprise($this->getUser());

        $form = $this->createForm(StageAltType::class, $stageAlt);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stageAlt = $form->getData();
            $manager->persist($stageAlt);
            $manager->flush();
            return $this->redirectToRoute('app_stage_alt_show',['id' => $stageAlt ->getId()]);

        }else {
            // recharge la page sinon
            return $this->render('stage_alt/create.html.twig', ['form' => $form->createView()]);
        }
    }

    #[Route('/stagealt/{id}/update', name: 'app_stage_alt_update')]
    public function update(Request $request, StageAlt $stageAlt, ManagerRegistry $doctrine)
    {
        // On vérifie si l'utilisateur a le rôle d'entreprise
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');
        // Vérifie si le stage ou l'alternance appartient bien à l'utilisateur connecté
        if ($stageAlt) {
            if ($stageAlt->getEntreprise() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas modifier un stage ou une alternance qui ne vous appartient pas .');

            }
        }
        // Création du formulaire pour mettre à jour les informations du stage ou de l'alternance
        $form = $this->createForm(StageAltType::class, $stageAlt);
        $form->handleRequest($request);

        // Récupération du gestionnaire d'entités à partir du registre de Doctrine

        $entityManager = $doctrine->getManager();
        // Vérifie si le formulaire a été soumis et est valide

        if ($form->isSubmitted() && $form->isValid()) {
            $stageAlt = $form->getData();
            $entityManager->persist($stageAlt);
            $entityManager->flush();
            // Redirection vers la page de détail du stage ou de l'alternance
            return $this->redirectToRoute('app_stage_alt_show', [
                'id' => $stageAlt ->getId(),
            ]);
        }
        // Affichage du formulaire de mise à jour
        return $this->renderForm('stage_alt/update.html.twig', [
            'form' => $form, 'stageAlt' => $stageAlt,
        ]);
    }

    #[Route('/stagealt/{id}/delete', name: 'app_stage_alt_delete')]
    public function delete(Request $request, StageAlt $stageAlt, ManagerRegistry $doctrine)
    {
        // On vérifie si l'utilisateur a le rôle d'entreprise
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');
        // Vérifie si le stage ou l'alternance appartient bien à l'utilisateur connecté
        if ($stageAlt) {
            if ($stageAlt->getEntreprise() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer un stage ou une alternance qui ne vous appartient pas .');

            }
        }
        // Récupération du gestionnaire d'entités à partir du registre de Doctrine

        $entityManager = $doctrine->getManager();
        // Création du formulaire pour supprimer un stage ou une alternance
        $form = $this->createForm(StageAltType::class, $stageAlt);
        $form->add('Delete', SubmitType::class);
        $form->add('Cancel', SubmitType::class);
        // traitement des données du formulaire

        $form->handleRequest($request);
        // Si l'utilisateur a cliqué sur le bouton "Delete"
        if ($form->getClickedButton() && 'Delete' === $form->getClickedButton()->getName()) {
            // suppression de l'entité de la base de données
            $entityManager->remove($stageAlt);
            $entityManager->flush();
            // Redirection vers la liste des stages ou alternances

            return $this->redirectToRoute('app_stage_alt_showEnt');
        }
        // si l'utilisateur a cliqué sur le bouton "Cancel"
        elseif ($form->isSubmitted() && 'Delete' !== $form->getName()) {
            // Redirection vers la page de détail du stage ou de l'alternance
            return $this->redirectToRoute('app_stage_alt_showEnt', [
                'id' => $stageAlt->getId(),
            ]);
        }
        // Affichage du formulaire de suppression
        return $this->renderForm('stage_alt/delete.html.twig', [
            'form' => $form, 'stageAlt' => $stageAlt,
        ]);
    }

    #[Route('/stagealt/candi', name: 'app_stage_alt_cand')]
    public function Candidate(StageAltRepository $stageAltRepository, Request $request)
    {
        //verification des roles
        if ($this->isGranted('ROLE_PROFESSEUR') or $this->isGranted('ROLE_ETUDIANT')){
            $looking = $request->query->get('search', '');
            //recuperation de tout les stages/alt
            $results = $stageAltRepository->search($looking);
            // recuperation de l'user , utile pour le filtrage
            $user = $this->getUser();
            // return la vue approprie
            return $this->render(
                'stage_alt/StageAltadd.html.twig',
                [
                    'results' => $results,
                    'user' => $user
                ]
            );
        }else{
            // redirection sinon
            return $this->redirectToRoute('app_stage_alt');
        }
    }


    #[Route('/stagealt/{id}/add', name: 'app_StageAlIdAdd')]
    public function add(StageAlt $stageAlt): Response
    {
        //verification des roles
        if ($this->isGranted('ROLE_PROFESSEUR') or $this->isGranted('ROLE_ETUDIANT')) {
            $user = $this->getUser();
            // return la vue approprie

            return $this->render('stage_alt/add.html.twig', ['stageAlt' => $stageAlt, 'user' => $user]
            );
        }else{
            // redirection sinon

            $this->redirectToRoute('app_stage_alt');
        }
    }


    #[Route('/stagealt/candi/{id}', name: 'app_stage_alt_candidater')]
    public function CandidateDB(StageAltRepository $stageAltRepository, $id, ManagerRegistry $doctrine){

        //verification du role
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $Manager = $doctrine->getManager();

        if ($id){
            $staAlt = $stageAltRepository->findOneBy(['id' => $id]);
            $user = $this->getUser();
            // ajout du stage au candidature
            $user -> addCandidature($staAlt);
            $Manager->persist($user);
            $Manager->flush();
            // redirection vers la liste des candidatures
            return $this->redirectToRoute('app_stage_alt_showCan');
        }
    }

    #[Route('/listeStaAlt', name: 'app_stage_alt_showEnt')]
    public function showEnt( ){
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');
        $user = $this->getUser();
        $results = $user -> getStageAlts();

        return $this->render('stage_alt/ent.html.twig', ['results' => $results]);
    }

    #[Route('/listeCandi', name: 'app_stage_alt_showCan')]
    public function showCan(){
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $user = $this -> getUser();
        return $this->render('stage_alt/stud.html.twig', ['user' => $user]
        );
    }



    #[Route('/stagealt/{id}/delCandi', name: 'app_stage_alt_delCandi')]
    public function deleteCandi(StageAlt $stageAlt){
        $user = $this->getUser();
        // redirection vers la page permettant de sup une candidature
        return $this->render('stage_alt/supprCand.html.twig', ['staAlt'=> $stageAlt, 'user'=>$user]);
    }


    #[Route('/stagealt/delCandi/{id}', name: 'app_stage_alt_delCandidature')]
    public function deleteCandiDB(StageAltRepository $stageAltRepository, $id, ManagerRegistry $doctrine){
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $Manager = $doctrine->getManager();

        if ($id){
            $staAlt = $stageAltRepository->findOneBy(['id' => $id]);
            $user = $this->getUser();
            // sup de candidature
            $user -> removeCandidature($staAlt);
            $Manager->persist($user);
            $Manager->flush();
            return $this->redirectToRoute('app_stage_halt_showCan');
        }
    }
    #[Route('ter_affect/{stageAlt_id}/{user_id}', name: 'affect_stage_to_user')]
    public function affectStageAlt(StageAltRepository $stageAltRepository, $stageAlt_id, $user_id, UtilisateurRepository $utilisateurRepository, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $stageAlt = $stageAltRepository->findOneBy(['id' => $stageAlt_id]);
        $utilisateur = $utilisateurRepository->findOneBy(['id' => $user_id]);
        if ($stageAlt) {
            if ($stageAlt->getEntreprise() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas affecter un stage/alterance qui ne vous appartient pas .');

            }
        }
        $manager = $doctrine->getManager();
        if ($utilisateur->getTer() === null) {
            //affectation
            $utilisateur->setstageAlt($stageAlt);
            //on enleve toutes ses demandes
            $utilisateur->removeCandidaturesStageAlt($stageAlt);
            $stageAlts = $manager->getRepository(StageAlt::class)->findAll();

            // enleve toutes les potentielles demandes de l'user
            foreach ($stageAlts as $stage) {
                if ($stage->getCandidats()->contains($utilisateur)) {
                    $stage->removeCandidatInteresed($utilisateur);
                    $manager->persist($stage);
                }
            }
            // plus dispo donc clear des etudiants
            $stageAlt->clearCandidats();
            // affectation
            $stageAlt->setEtudiantRetenu($utilisateur);
            $manager->flush();
            $manager->persist($utilisateur);
            $manager->persist($stageAlt);
            $manager->flush();
        }
        return $this->redirectToRoute('app_stage_alt_showEnt');
    }


    #[Route('ter_delete/{stageAlt_id}/{user_id}', name: 'unaffect_stage_to_user')]
    public function cancelStageAlt(StageAltRepository $stageAltRepository ,$stageAlt_id ,$user_id, UtilisateurRepository $utilisateurRepository, ManagerRegistry $doctrine){
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $stageAlt=$stageAltRepository->findOneBy(['id'=>$stageAlt_id]);
        $utilisateur = $utilisateurRepository->findOneBy(['id'=>$user_id]);
        if ($stageAlt) {
            if ($stageAlt->getEntreprise() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas annuler la demande d un TER qui ne vous appartient pas .');

            }
        }
        $manager= $doctrine->getManager();
        //enleve le stage des candidatures (user)
        $utilisateur->removeCandidaturesStageAlt($stageAlt);
        //enleve l'utilisateur des candidatures (stage)
        $stageAlt->removeCandidatInteresed($utilisateur);
        $manager->persist($utilisateur);
        $manager->persist($stageAlt);
        $manager->flush();
        return $this->redirectToRoute('app_stage_alt_showEnt');
    }


















    #[Route('/removeStageAlt/{id}', name:'app_stage_alt_delCandidature')]
    public function removeTerFromCandidature(StageAlt $stageAlt, ManagerRegistry $doctrine)
    {
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();

            // Rechercher le stage/ter à supprimer dans la liste des candidatures de l'utilisateur
            $staaltsupp = $user->getCandidatures()->contains($stageAlt);

            if (!$staaltsupp) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'TER not found'
                ], 404);
            }

            // Supprimer le stage/ter de la liste des candidatures de l'utilisateur
            $user->removeOneCandidaturesStageAlt($stageAlt);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->persist($stageAlt);

            $entityManager->flush();

            return $this->redirectToRoute('app_stage_alt_showCan');

    }


}
