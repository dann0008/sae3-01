<?php

namespace App\Controller;

use App\Entity\Ter;
use App\Form\TerType;
use App\Repository\TerRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TerController extends AbstractController
{

    #[Route('/teradd', name: 'app_teradd')]
    public function StudentIndex(TerRepository $terRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $looking = $request->query->get('search', '');
        $results = $terRepository->search($looking);
        $user = $this->getUser();

        return $this->render(
            'ter/tersadd.html.twig',
            [
                'results' => $results,
                'user' => $user
            ]
        );
    }

    // fonction show qui s'adapte en fonction du type
    #[Route('ter/{id}', name: 'app_terId')]
    public function show(Ter $ter ,Request $request): Response
    {
        // acces refusé aux utilisateur non connécté
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new Response('Access Denied', 403);
        }
        $user = $this->getUser();
        $role = $user->getRoles();
        $prop = False;
        // acces refuser aux entreprises
        if(in_array('ROLE_ENTREPRISE',$role)){
            return new Response('Access Denied',403);
        }
        // verification du proprietaire
        if ($ter->getCreateur() == $user) {
            $prop = True;
        }
        // sert à l'appel dans le (voir + )
        if($request->query->get('no_include') == 'true') {
            // return pareil que showProf sauf que ca permet de bien l'inserer dans la liste de ses ters
            return $this->render('ter/showProfInterface.html.twig', ['ter' => $ter,'role'=>$role]);
        }
        if (!$prop) {
            // return le ter
            return $this->render('ter/show.html.twig', ['ter' => $ter]);
        } else {
            // return le ter du propriétaire (avec les demandes )
            return $this->render('ter/showProf.html.twig', ['ter' => $ter]);
        }


    }

    // return la vue permettant de candidater
    #[Route('ter/{id}/add', name: 'app_terIdAdd')]
    public function add(Ter $ter): Response
    {
        $user = $this->getUser();
        return $this->render('ter/add.html.twig', ['ter' => $ter, 'user' => $user]
        );
    }


    // ajoute la ter aux candidatures de l'user
    #[Route("ter/add/{ter_id}", name: "add_ter_to_user")]
    public function addTerToUser(TerRepository $terRepository, $ter_id, ManagerRegistry $doctrine)

    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $Manager = $doctrine->getManager();
        if ($ter_id) {
            // ajout du ter si valide

            $ter = $terRepository->findOneBy(['id' => $ter_id]);
            $user = $this->getUser();
            $user->addCandidatureter($ter);
            $Manager->persist($user);
            $Manager->flush();
            return $this->redirectToRoute('app_teradd');
        } else {
            throw $this->createAccessDeniedException('Vous ne pouvez ajouter ce TEr a votre liste de souhait  .');

        }
    }

    // liste de ses choix de ter ou du ter affectéé
    #[Route('/terStud', name: 'app_terstud')]
    public function showStud(): Response
    {
        // restriction de role
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $user = $this->getUser();
        return $this->render('ter/showStud.html.twig', ['user' => $user]
        );
    }

    // liste de ses ters
    #[Route('/terProf', name: 'terProf')]
    public function showprof(TerRepository $terRepository, Request $request): Response

    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');
        $user = $this->getUser();
        // pour l'affichage
        $prof = $user->getPrenom() . ' ' . $user->getNom();
        $looking = $request->query->get('searchProf', $user);
        $results = $terRepository->searchProf($looking);
        // transmet son identité
        return $this->render('ter/prof.html.twig', ['results' => $results, 'prof' => $prof]);
    }



    #[Route('ter/update/{id?0}', name: 'terUpdate')]
    public function update(Ter $ter = null, Request $request, ManagerRegistry $doctrine, TerRepository $terRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');
        //verif propriétaire
        if ($ter) {
            if ($ter->getCreateur() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas modifier un TER qui ne vous appartient pas .');

            }
        }
        if (!$ter) {
            $ter = new Ter();
        }
        $manager = $doctrine->getManager();
        $form = $this->createForm(TerType::class, $ter);
        $form->handleRequest($request);
        $ancien = $terRepository->find($ter);
        // ancien Titre
        $oldTitle = $ancien->getNomSujet();
        if ($form->isSubmitted() && $form->isValid()) {
            $ter = $form->getData();
            //nouveau titre mit
            $title = $ter->getNomSujet();
            // verification si le titre est pas deja applique
            $exist = $terRepository->findOneBy(['nomSujet' => $title]);
            // si le nouveau et l'ancien sont differents
            if ($title != $oldTitle) {
                // si le titre appartient deja a un autre
                if ($exist) {
                    $form->get('NomSujet')->addError(new FormError('Ce titre est déjà utilisé.'));

                    return $this->render('ter/update.html.twig', ['form' => $form->createView(),'ter'=>$ter]);

                }
            }
            $manager->flush();
            return $this->redirectToRoute('app_terId', ['id' => $ter->getId()]);

        } else {

            return $this->render('ter/update.html.twig', ['form' => $form->createView(),'ter'=>$ter]);
        }
    }


    // fonction permettant d'affecter un  ter à un utilisateur
    #[Route('teraffect/{ter_id}/{user_id}', name: 'affect_ter_to_userr')]
    public function affecterTEr(TerRepository $terRepository, $ter_id, $user_id, UtilisateurRepository $utilisateurRepository, ManagerRegistry $doctrine): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');
        $ter = $terRepository->findOneBy(['id' => $ter_id]);
        $utilisateur = $utilisateurRepository->findOneBy(['id' => $user_id]);
        if ($ter) {
            if ($ter->getCreateur() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas affecter un TER qui ne vous appartient pas .');

            }
        }

        $manager = $doctrine->getManager();
        if ($utilisateur->getTer() === null) {
            // affecte à l'etudiant le ter
            $utilisateur->setTer($ter);
            $utilisateur->removeCandidaturesTer($ter);
            $ters = $manager->getRepository(Ter::class)->findAll();
            // enleve toutes les potentielles demandes de l'user
            foreach ($ters as $terj) {
                if ($terj->getCandidatsInteresed()->contains($utilisateur)) {
                    $terj->removeCandidatInteresed($utilisateur);
                    $manager->persist($terj);
                }
            }
            $ter->clearDemandes();
            // affecte au ter l'etudiant
            $ter->setEtudiant($utilisateur);
            $manager->persist($utilisateur);
            $manager->persist($ter);
            $manager->flush();
        }else {
            throw $this->createAccessDeniedException('Ce TER est déjà affecté .');

        }
        return $this->redirectToRoute('terProf');
    }

    // fonction permettant de refuser un  ter à un utilisateur
    #[Route('terdelete/{ter_id}/{user_id}', name: 'unaffect_ter_to_userr')]
    public function cancelTer(TerRepository $terRepository ,$ter_id ,$user_id, UtilisateurRepository $utilisateurRepository, ManagerRegistry $doctrine): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');

        $ter=$terRepository->findOneBy(['id'=>$ter_id]);

        $utilisateur = $utilisateurRepository->findOneBy(['id'=>$user_id]);
        if ($ter) {
            if ($ter->getCreateur() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas annuler la demande d un TER qui ne vous appartient pas .');

            }
        }
        $manager= $doctrine->getManager();

        //enleve le ter de la liste de candidature du utilisateur
        $utilisateur->removeCandidaturesTer($ter);
        //eneleve le utilisateur de la liste de candidature du ter

        $ter->removeCandidatInteresed($utilisateur);
        $manager->persist($utilisateur);
        $manager->persist($ter);

        $manager->flush();
        return $this->redirectToRoute('terProf');

    }


    #[Route('ter_create', name: 'terCreate')]
    public function create(Request $request, ManagerRegistry $doctrine, TerRepository $terRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');
        $manager = $doctrine->getManager();
        $ter = new Ter();
        // pré-remplissage
        $ter->setCreateur($this->getUser());
        $ter->setEtudiant(null);
        $form = $this->createForm(TerType::class, $ter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ter = $form->getData();
            $title = $ter->getNomSujet();
            $exist = $terRepository->findOneBy(['nomSujet' => $title]);
            if ($exist) {
                $form->get('NomSujet')->addError(new FormError('Ce titre est déjà utilisé.'));

                return $this->render('ter/create.html.twig', ['form' => $form->createView()]);

            }
            $manager->persist($ter);
            $manager->flush();
            $this->addFlash('success', '
            Votre TER à été créé');
            return $this->redirectToRoute('app_terId', ['id' => $ter->getId()]);

        } else {

            return $this->render('ter/create.html.twig', ['form' => $form->createView()]);
        }
    }


    #[Route('ter/{id}/delete ', name: 'terdelete')]
    public function delete(Ter $ter , ManagerRegistry $doctrine, Request $request ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROFESSEUR');
        if ($ter) {
            if ($ter->getCreateur() !== $this->getUser()) { //&& !$this->isGranted('ROLE_ADMIN') ne marche pas
                throw $this->createAccessDeniedException('Vous ne pouvez pas Supprimer un TER qui ne vous appartient pas .');

            }
        }
        $entityManager = $doctrine->getManager();
        // creation formulaire de suppression
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Delete'])
            ->add('cancel', SubmitType::class, ['label' => 'Cancel'])
            ->getForm();
        $form->handleRequest($request);
        // verif formulaire soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // si delete
            if ($form->get('delete')->isClicked()) {
                // supprime le ter
                $entityManager->remove($ter);
                $entityManager->flush();
                $this->addFlash('delete', 'Le Ter a été supprimé');

                return $this->redirectToRoute('terProf');
            } else {
                // autre (cancel)
                return $this->redirectToRoute('terProf');
            }
        } else {
            return $this->renderForm('ter/delete.html.twig', ['form' => $form, 'ter' => $ter]);
        }
    }




   #[Route('/removeTer/{id}', name:'app_ter_remove')]
    public function removeTerFromCandidature(Ter $ter, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $cutoffDate = new \DateTime('2023-05-10');
        $currentDate = new \DateTime();
        $modifiable = $currentDate < $cutoffDate;
        if ($modifiable) {

            // Récupérer l'utilisateur connecté
            $user = $this->getUser();

            // Rechercher le TER à supprimer dans la liste des candidatures de l'utilisateur
            $terSupp = $user->getCandidaturesTers()->contains($ter);

            if (!$terSupp) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'TER not found'
                ], 404);
            }

            // Supprimer le TER de la liste des candidatures de l'utilisateur
            $user->removeOneCandidaturesTer($ter);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->persist($ter);

            $entityManager->flush();

            return $this->redirectToRoute('app_terstud');
        }
        else {
            {
                return new Response("Vous ne pouvez malheuresement plus modifier vos choix ");
            }
        }

    }



}
