<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurForm;
use App\Repository\ClasseRepository;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $utilisateurs = $utilisateurRepository->findBy([],['nom' => 'ASC', 'prenom' => 'ASC']);

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/profil', name: 'app_utilisateur_profil')]
    public function profil(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('utilisateur/profil.html.twig', ['utilisateur' => $this->getUser()]);
    }

    #[Route('/accueil', name: 'app_utilisateur_accueil')]
    public function accueil(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isGranted('ROLE_PROFESSEUR' )){
            return $this->render('home/accueilProf.html.twig');
        }

        elseif($this->isGranted('ROLE_ETUDIANT' )){
            return $this->render('home/accueilEtud.html.twig');
        }
        elseif($this->isGranted('ROLE_ENTREPRISE' )){
            return $this->render('home/accueilEntr.html.twig');
        }
        elseif($this->isGranted('ROLE_ADMIN' )){
            return $this->redirectToRoute('admin');
        }else{
            return $this->render('home/index.html.twig');
        }

    }

    #[Route('/utilisateur/{utilisateur}/cv', name: 'app_utilisateur_cv')]
    public function cv(Request $request, FileUploader $fileUploader, Utilisateur $utilisateur, ManagerRegistry $doctrine): Response
    {
        if (!$this->isGranted('ROLE_ETUDIANT') and !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Access Denied.');
        }
        if ($this->getUser()->getId() != $utilisateur->getId() and !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_utilisateur_show', ['utilisateur' => $this->getUser()->getId()]);
            }
        $form = $this->createForm(UtilisateurForm::class, $utilisateur);
        $entityManager = $doctrine->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cvFile = $form->get('cv')->getData();
            if ($cvFile) {
                $cvFileName = $fileUploader->upload($cvFile);
                $utilisateur->setCv($cvFileName);
            }
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_show', ['utilisateur' => $utilisateur->getId()]);
        }
        return $this->renderForm('utilisateur/cv.html.twig', [
            'form' => $form,
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/utilisateur/{utilisateur}/cv/delete', name: 'app_utilisateur_cv_delete')]
    public function deleteCv(Request $request, Utilisateur $utilisateur, ManagerRegistry $doctrine): Response
    {
        if (!$this->isGranted('ROLE_ETUDIANT') and !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Access Denied.');
        }
        if ($this->getUser()->getId() != $utilisateur->getId() and !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_utilisateur_show', ['utilisateur' => $this->getUser()->getId()]);
        }
        $form = $this->createFormBuilder($utilisateur)
            ->add('delete', SubmitType::class)
            ->add('cancel', SubmitType::class)
            ->getForm();

        $entityManager = $doctrine->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $utilisateur->setCv(null);
                $entityManager->persist($utilisateur);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_utilisateur_show', ['utilisateur' => $utilisateur->getId()]);
        }
        return $this->render('utilisateur/deleteCv.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }


    #[Route('/trombinoscope/etudiants', name: 'app_utilisateur_etudiant')]
    public function etudiantIndex(UtilisateurRepository $utilisateurRepository, ClasseRepository $classeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $etudiants = $utilisateurRepository->findAllStudentsWithClasse();
        $classes = $classeRepository->findBy([],['nom' => 'ASC']);

        return $this->render('utilisateur/etudiant.html.twig', ['etudiants' => $etudiants,'classes' => $classes]);
    }

    #[Route('trombinoscope/entreprises', name: 'app_utilisateur_entreprise')]
    public function entrepriseIndex(UtilisateurRepository $utilisateurRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $entreprises = $utilisateurRepository->findAllEnterprise();

        return $this->render('utilisateur/entreprise.html.twig', ['entreprises' => $entreprises]);
    }

    #[Route('trombinoscope/personnels', name: 'app_utilisateur_personnel')]
    public function personnelIndex(UtilisateurRepository $utilisateurRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $personnels = $utilisateurRepository->findAllEmployee();

        return $this->render('utilisateur/personnel.html.twig', ['personnels' => $personnels]);
    }


    #[Route('utilisateur/{utilisateur}', name: 'app_utilisateur_show')]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', ['utilisateur' => $utilisateur]
        );
    }

    #[Route('/ters', name: 'app_utilisateur_ter')]
    public function ter( ): Response{
        if (!$this->isGranted(['ROLE_PROFESSEUR', 'ROLE_ETUDIANT'])) {

            $user = $this->getUser();
            if (in_array('ROLE_PROFESSEUR', $user->getRoles())) {
                return $this->redirectToRoute('terProf');

            }
            $ters = $user->getTer();
            $nom = $user->getNom();
          //  return $this->redirectToRoute('app_terstud');

            return $this->render('ter/showStud.html.twig', ['user' => $user]);
            //return $this->render('utilisateur/ter.html.twig', ['ters' => $ters, 'nom' => $nom]);
        }else{
            return $this->redirectToRoute('app_ter');

        }
}
    #[Route('/stageAlt', name: 'app_utilisateur_stageAlt')]
    public function stageAlt( ): Response{
        if (!$this->isGranted(['ROLE_ENTREPRISE', 'ROLE_ETUDIANT'])) {

            $user = $this->getUser();
            if (in_array('ROLE_ENTREPRISE', $user->getRoles())) {
                return $this->redirectToRoute('app_stage_alt_showEnt');

            }
            $ters = $user->getTer();
            $nom = $user->getNom();
            //  return $this->redirectToRoute('app_terstud');

            return $this->render('stage_alt/stud.html.twig', ['user' => $user]);
            //return $this->render('utilisateur/ter.html.twig', ['ters' => $ters, 'nom' => $nom]);
        }else{
            return $this->redirectToRoute('app_ter');

        }
    }
}
